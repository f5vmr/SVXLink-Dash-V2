#!/usr/bin/perl
use warnings;
use strict;
use utf8;
use Time::Piece;
use File::stat;

my $entry;
my @array;
my @DataTabFN;
my %DataTabFN;
my %DataTabMonTG;
my %DataTabActTG;
my %DataTabCurrAct;
my $callfound;
my $nn;
my $call;
my $rawcall;
my $tg1;
my $tg2;
my $fm_funknetz_content;
my $log_time = "";
my @valid_mon_tgs;
my $vmt_org = 0;
my $vmt = 0;
my $reload = 0;
my $acttg = 0;
my $at_least_one_curr = 0;

my $verbose = 0;
# prüfen ob Heimnetz erreichbar ist, keine Aktion, falls aktiv
# 10.3.0.1 ist das ggü liegende tun interface
my $tm = localtime(time);
my $tgdatei_upd = 0;
my $version = "2.20";

    my $cmd = "pwd";
	my $dir =`$cmd`;
	$dir = trim_cr($dir);
    my $tgdatei = $dir . "/dl3el/mon-all.log";
	my $logdatei = $dir  . "/dl3el/get-monitor.log";
    printf "DIR: %s Logdatei: %s %s\n",$dir,$logdatei,$tgdatei;
	STDOUT->autoflush(1);
#    open(LOG, ">/home/svxlink/dl3el/get-monitor.log") or die "Fehler bei Logdatei: $tgdatei\n";
	open(LOG, ">$logdatei") or die "Fehler bei Logdatei: $logdatei\n";
    printf LOG "DIR: %s Logdatei: %s %s\n",$dir,$logdatei,$tgdatei;
	printf LOG "DL3EL Monitor TG [v$version] Start: %02d:%02d:%02d am %02d.%02d.%04d\n$0 @ARGV\n",$tm->hour, $tm->min, $tm->sec, $tm->mday, $tm->mon,$tm->year;

	my $total = $#ARGV + 1;
	my $counter = 1;
	foreach my $a(@ARGV) {
		print "Arg # $counter : $a\n" if ($verbose == 7);
		$counter++;
		if (substr($a,0,2) eq "v=") {
		    $verbose = substr($a,2,1);
		    print "Debug On, Level: $verbose\n" if $verbose;
		} 
		if (substr($a,0,2) eq "r=") {
		    $reload = substr($a,2,1);
		    print "Reload: $reload\n" if $verbose;
		} else {
            print "ARG_b: $a\n" if $verbose;
            $valid_mon_tgs[$vmt_org] = trim_plus($a);
            ++$vmt_org;
        }
	}
    $vmt = $vmt_org;
    --$vmt;
    $acttg = $valid_mon_tgs[$vmt];
    printf LOG "$counter (0)TG%s: %s [%s]\n",$vmt,$valid_mon_tgs[$vmt],$acttg if $verbose;
    $acttg = $valid_mon_tgs[$vmt];
    --$vmt;
    while ($vmt) {
        printf LOG "(1a)TG%s: %s [%s]\n",$vmt,$valid_mon_tgs[$vmt],$acttg;
        if ($acttg eq $valid_mon_tgs[$vmt]) {
            --$vmt_org;
            last;
        }    
        printf LOG "(1b)TG%s: %s [%s]\n",$vmt,$valid_mon_tgs[$vmt],$acttg;
        --$vmt;
    }
    $vmt = $vmt_org;
#    while ($vmt) {
#        --$vmt;
#        printf "(2)TG%s: %s [%s]\n",$vmt,$valid_mon_tgs[$vmt],$acttg;
#    }
# exit;

    read_tgdatei($tgdatei);
    $tgdatei_upd = file_date_time($tgdatei);
   	printf LOG "Zeitstempel: %s \n",$tgdatei_upd if $verbose;
    getfm_funknetz();
   	if ($at_least_one_curr) {
        print LOG "get curr_act\n";
        print_curr_act($tgdatei_upd);
    }    
   	print LOG "get act tg\n";
    print_act_tg();
   	print LOG "get mon tg\n";
    print_mon_tg($tgdatei_upd);
   	print LOG "close log\n";
    close LOG;
    exit 0;

sub getfm_funknetz {
my @mon_array;
my $mon_tg;
my $mon_tgi;
my $active_tg;
my $act_tg;
my $mm = 0;
my $curr_act = 0;
#<table>
#   <tr style=""><td style="padding:5px;padding-right:20px;white-space:nowrap;">9V1LH-L</td><td style="padding:5px;padding-right:20px;">0&nbsp;/&nbsp;26426</td><td>Monitor:&nbsp;8&nbsp;/&nbsp;9&nbsp;/&nbsp;262&nbsp;/&nbsp;2623&nbsp;/&nbsp;26322&nbsp;/&nbsp;26426</td></tr><tr style=""><td style="padding:5px;padding-right:20px;white-space:nowrap;">DB0AM</td><td style="padding:5px;padding-right:20px;">0&nbsp;/&nbsp;26385</td><td>Monitor:&nbsp;26385&nbsp;/&nbsp;26390</td></tr><tr style=""><td style="padding:5px;padding-right:20px;white-space:nowrap;">DB0AMK</td><td style="padding:5px;padding-right:20px;">26207&nbsp;/&nbsp;39590</td><td>Monitor:&nbsp;2&nbsp;/&nbsp;1262&nbsp;/&nbsp;14774&nbsp;/&nbsp;19348&nbsp;/&nbsp;26207&nbsp;/&nbsp;39128&nbsp;/&nbsp;39590</td></tr>
#
#<tr style=""><td style="padding:5px;padding-right:20px;white-space:nowrap;">DB0HTV</td><td style="padding:5px;padding-right:20px;">0&nbsp;/&nbsp;26269</td><td>Monitor:&nbsp;2&nbsp;/&nbsp;2626&nbsp;/&nbsp;26267&nbsp;/&nbsp;26269</td></tr><tr style=""><td style="padding:5px;padding-right:20px;white-space:nowrap;">DB0HTV-XLX</td><td style="padding:5px;padding-right:20px;">0&nbsp;/&nbsp;26269</td><td>Monitor:&nbsp;26269</td></tr>
#<tr style=""><td style="padding:5px;padding-right:20px;white-space:nowrap;">DL3EL</td><td style="padding:5px;padding-right:20px;">0&nbsp;/&nbsp;26269</td><td>Monitor:&nbsp;777&nbsp;/&nbsp;26269&nbsp;/&nbsp;262649</td></tr>
#<tr style="color:#008000;"><td style="padding:5px;padding-right:20px;white-space:nowrap;">DB0BH</td><td style="padding:5px;padding-right:20px;">91593&nbsp;/&nbsp;91593</td><td>Monitor:&nbsp;2&nbsp;/&nbsp;1000&nbsp;/&nbsp;1080&nbsp;/&nbsp;1084&nbsp;/&nbsp;1089&nbsp;/&nbsp;1262&nbsp;/&nbsp;2628&nbsp;/&nbsp;26284&nbsp;/&nbsp;91472&nbsp;/&nbsp;91593&nbsp;/&nbsp;96126&nbsp;/&nbsp;97265&nbsp;/&nbsp;97348&nbsp;/&nbsp;97475</td></tr>
    printf "\n\nLese fm_funknetz_: %s<br>\nVERARBEITUNG\n",$fm_funknetz_content if ($verbose >=4);
    $nn = 0;
    $mm = 0;
    @array = split (/<\/tr>/, $fm_funknetz_content);

    foreach $entry (@array) {
		print "Entry($nn) [$entry]\n" if ($verbose >3);
        if (substr($entry,11,5) eq "color") {
            $curr_act = 1;
            $at_least_one_curr = 1;
        } else {
            $curr_act = 0;
        }    
		$callfound = ($entry =~ /.*<td style=\"padding:5px;padding-right:20px;white-space:nowrap;\">([\w-]+)<\/td>/i)? $1 : "undef";
        printf "Call: %s\n",$callfound if ($verbose >3);
        if ($callfound ne "undef") {
            $call = ($callfound =~ /([\w]+)([-\w]*)/i)? $1 : "undef";
            $DataTabFN{$call}{'CALL'} = $call;
            $tg1 = ($entry =~ /.*<td style=\"padding:5px;padding-right:20px;white-space:nowrap;\">([\w-]+)<\/td><td style="padding:5px;padding-right:20px;">([\w]+)&nbsp;\/&nbsp;([\w]+)<\/td><td>Monitor:(.*)<\/td>/i)? $2 : "undef";
            printf "$call TGs: %s %s / %s Monitor TGs: %s ->\n",$1,$2,$3,$4 if ($verbose >3);
            print "MonTGs:" if ($verbose >3);
            $active_tg = $2;
            if ($active_tg ne "0") {
                print "Active TG: $active_tg \n" if ($verbose >4);
                $DataTabActTG{$active_tg}{'TG'} = $active_tg;
                ++$DataTabActTG{$active_tg}{'USER'};
            }    
            @mon_array = split (/&nbsp;/, $4);
            $DataTabFN{$call}{'CURR_ACT'} = $curr_act;
            $DataTabFN{$call}{'CURR_ACT_TG'} = $active_tg if $curr_act;
            $DataTabFN{$call}{'VALID'} = 0;
            foreach $mon_tg (@mon_array) {
                if (($mon_tg ne "") && ($mon_tg ne "/")) {
                    print "$mon_tg Vergleich mit " if ($verbose >3);
                    while ($vmt) {
                        --$vmt;
                        print "($vmt) $valid_mon_tgs[$vmt]" if ($verbose >3);
                        if ($mon_tg eq $valid_mon_tgs[$vmt]) {
                            $DataTabFN{$call}{'MTGS'}[$mm] = $mon_tg;
                            print "(gültig)" if ($verbose >3);
                            $DataTabFN{$call}{'VALID'} = 1;
                            ++$mm;
                        }
                        print " " if ($verbose >3);
                    }
                    $vmt = $vmt_org;        
                    $DataTabFN{$call}{'VALID_CNTR'} = $mm;
                }    
            }
            print "\n" if ($verbose >3);
            if ($tg1 ne "undef") {
                $tg2 = $3;	
            }
            if ($DataTabFN{$call}{'VALID'} == 1) {
                printf "FunknetzTG Call [%s] gelesen, %s Monitor TGs ($mm)\n<br>",$call,$mm-1 if ($verbose >3);
                $DataTabFN{$call}{'ADDON'} = $2;
                $DataTabFN{$call}{'TGS'} = $tg1 . "/" . $tg2;
                printf "FunknetzTG %s gelesen, TG1=%s, TG2=%s [$callfound / $2]\n<br>",$call,$tg1,$tg2 if ($verbose >3);
                print "MonitorTGs: ($mm) " if ($verbose >3);
                while ($mm) {
                    --$mm;
                    printf "%s, ",$DataTabFN{$call}{'MTGS'}[$mm] if ($verbose >3);
                }    
                print "\n" if ($verbose >3);
                ++$nn;
#                last if ($nn > 40);
            }
         }
	}
    printf "$nn gueltige FunknetzTGs gelesen und in Tabelle geschrieben<br>" if ($verbose >3);
    $nn=0;
    foreach $call (sort keys %DataTabFN) {
        if ($DataTabFN{$call}{'VALID'} == 1) {
            print "$DataTabFN{$call}{'CALL'} [$call]" if ($verbose >3);
            $mm = $DataTabFN{$call}{'VALID_CNTR'};
            while ($mm) {
                --$mm;
                printf "($mm)%s, ",$DataTabFN{$call}{'MTGS'}[$mm] if ($verbose >3);
                $mon_tg = $DataTabFN{$call}{'MTGS'}[$mm];
                ++$nn;
                $mon_tgi = sprintf ("%07d.%03d",$DataTabFN{$call}{'MTGS'}[$mm],$nn);
                $DataTabMonTG{$mon_tgi}{'MONTG'} = $mon_tg;
                $DataTabMonTG{$mon_tgi}{'CALL'} = $call;
                printf "NT: ($mm)%s [Index:%s] %s:NT ",$mon_tg,$mon_tgi,$DataTabMonTG{$mon_tgi}{'CALL'} if ($verbose >3);
            }    
        }
        if ($DataTabFN{$call}{'CURR_ACT'} == 1) {
            $DataTabCurrAct{$call}{'CALL'} = $DataTabFN{$call}{'CALL'};
            $DataTabCurrAct{$call}{'CURR_ACT_TG'} = $DataTabFN{$call}{'CURR_ACT_TG'};
        }    
    }	
    
}

sub print_curr_act {
my $act_tg;
my $zz = 0;

    printf "<tr height=25px><th>aktuell aktive Calls (TG) %s</th></tr><tr><td>",$_[0];
    print "<tr><td><form method=\"post\">";
    print LOG "aktuell Active Calls:\n";
    foreach $call (sort keys %DataTabCurrAct) {
        printf "<button type=submit id=jmptoA name=jmptoA class=active_id value=%s>%s(%s) </button>",$DataTabCurrAct{$call}{'CURR_ACT_TG'},$call,$DataTabCurrAct{$call}{'CURR_ACT_TG'};
        printf LOG "%s(%s) ",$call,$DataTabCurrAct{$call}{'CURR_ACT_TG'};
        if (++$zz > 11) {
            print "<br>" ;
            $zz = 0;
        }    
    }    
    print "</td></tr></form><br>";
    print LOG "\n";
}

sub print_act_tg {
my $act_tg;
my $zz = 0;

    print "<tr height=25px><th>aktuell aktive TGs</th></tr><tr><td>";
    print "<tr><td><form method=\"post\">";
    print LOG "aktuell Active TGs:\n";
    foreach $act_tg (sort keys %DataTabActTG) {
        printf "<button type=submit id=jmptoA name=jmptoA class=active_id value=%s>%s(%s) </button>",$DataTabActTG{$act_tg}{'TG'},$DataTabActTG{$act_tg}{'TG'},$DataTabActTG{$act_tg}{'USER'};
        printf LOG "%s(%s) ",$DataTabActTG{$act_tg}{'TG'},$DataTabActTG{$act_tg}{'USER'};
        if (++$zz > 11) {
            print "<br>" ;
            $zz = 0;
        }    
    }    
    print "</td></tr></form><br>";
    print LOG "\n";
}

sub print_mon_tg {
my $mon_tg;
my $mon_tgi;
my $mon_tg_act = 0;
my $zz = 0;
my $ii = 0;

    print "\nTG Monitoring Liste:\n" if ($verbose >3);
    $log_time = act_time();
    printf "<tr height=25px><th>(Script Run: $log_time) Nutzer Monitor TGs (Netz Update %s)</th></tr><tr><td>",$_[0];
    printf LOG "(Script Run: $log_time) Nutzer Monitor TGs (Netz Update %s)\n",$_[0];
#    printf "(Script Run: $log_time) Nutzer Monitor TGs (Netz Update %s)",$_[0];
    print "<tr><td>";
    foreach $mon_tgi (sort keys %DataTabMonTG) {
        $mon_tg = $DataTabMonTG{$mon_tgi}{'MONTG'};
        print "$mon_tg_act eq $mon_tg? [Index: $mon_tgi]\n" if ($verbose >3);
        if ($mon_tg_act ne $mon_tg) {
#            printf "</td></tr><tr><td><b>TG %s</b></td></tr><tr><td>", $mon_tg;
            printf "</td></tr><tr><td><form method=\"post\"><button type=submit id=jmptoA name=jmptoA class=active_id value=%s>TG %s</button></form>",$mon_tg,$mon_tg;
            printf LOG "\n%s\n ",$mon_tg;
            $mon_tg_act = $mon_tg;
            $nn = 0;
            $zz = 0;
        }   
        if (++$zz > 11) {
            print "<br>" ;
            $zz = 0;
        }    
#        print ", " if ($nn++);
        printf "%s ",$DataTabMonTG{$mon_tgi}{'CALL'} if ($verbose >=0);
        printf LOG "%s ",$DataTabMonTG{$mon_tgi}{'CALL'};
        ++$ii;
    }	
    print "</td></tr>";
}

sub file_date_time {
	my $tgdatei = $_[0];
    my $tm = localtime(stat($tgdatei)->mtime);
	return (sprintf("%02d.%02d.%04d %02d:%02d:%02d",$tm->mday, $tm->mon,$tm->year,$tm->hour, $tm->min, $tm->sec));
}
sub act_date_time {
	my $tm = localtime(time);
	return (sprintf("%02d.%02d.%04d %02d:%02d:%02d",$tm->mday, $tm->mon,$tm->year,$tm->hour, $tm->min, $tm->sec));
}

sub act_time {
	my $tm = localtime(time);
	return (sprintf("%02d:%02d:%02d",$tm->hour, $tm->min, $tm->sec));
}

sub read_tgdatei {
    my $tgdatei = $_[0];
    my $tgdatei_neu = $_[0] . ".neu";
    my $tgdatei_org = 0;
# wenn die tgdatei nicht existiert, führt der Zugriff auf ->mtime zu einem Abbruich. Das wird mit dem eval verhindert
eval {
    $tgdatei_org = stat($tgdatei)->mtime;
};
    my $tm = time();
    my $delta = $tm-$tgdatei_org;
    my $cmd = "";
    my $tx; 
    
#    $delta = 500 if ($reload);
    $log_time = act_date_time();
    printf LOG "[$log_time] Delta: %s ($tm), Status Reload:%s\n",$delta,$reload;

    if (($delta > 300) || ($reload)) {
        $cmd = "wget --tries=2 --timeout=5 -O " . $tgdatei_neu . " -q https://www.funkerportal.de/fm-funknetz?mon=1";
        printf LOG "Datei vom Funknetz holen: %s\n",$cmd;
        $tx =`$cmd`;
        printf LOG "Datei geholt, jetzt lesen\n";
        read_content($tgdatei_neu);
    } else {
        print LOG "Datei aus Cache holen\n";
        read_content($tgdatei);
    }

    printf LOG "Länge Daten: %s\n",length($fm_funknetz_content);
    
    if (length($fm_funknetz_content) < 100000) {
        printf LOG "$log_time aktuelle Daten zu Monitor Calls von FM-Funknetz nicht verfügbar (%s)<br>\n",length($tgdatei_neu);
        read_content($tgdatei);
    } else {    
        $cmd = "cp $tgdatei_neu $tgdatei";
        $tx =`$cmd`;
        print LOG "Cache aktualisiert: $cmd\n";
        printf LOG "$log_time aktuelle Daten zu Monitor Calls von FM-Funknetz geholt (Delta war $delta)<br>\n";
    }    
}    

sub read_content {
    open(INPUT, $_[0]) or die "Fehler bei Eingabedatei: $_[0] [$0: open $_[0]: $!]\n";
    {
        local $/;#	
        $fm_funknetz_content = <INPUT>;
        printf "$log_time FM-Funknetz Daten aus Cache geholt<br>\n" if $verbose;
    }    
    close INPUT;
}

sub trim_plus {
	my $string = $_[0];
	$string = shift;
	$string =~ s/\+//g;
	return $string;
}

sub trim_cr {
	my $string = $_[0];
	$string = shift;
	$string =~ s/\n//g;
	return $string;
}
