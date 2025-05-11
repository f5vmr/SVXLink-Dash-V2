#!/usr/bin/perl
use warnings;
use strict;
use utf8;
use Time::Piece;
use File::stat;

my $entry;
my @array;
my %CurrentLoginsTab;
my @DataTabFN;
my %DataTabFN;
my %DataTabMonTG;
my %DataTabActTG;
my $callfound;
my $nn;
my $call;
my $rawcall;
my $tg1;
my $tg2;
my $el_content;
my $log_time = "";
my @valid_mon_tgs;
my $vmt_org = 0;
my $vmt = 0;
my $reload = 0;
my $srch_pref = "";;

my $verbose = 0;
# prüfen ob Heimnetz erreichbar ist, keine Aktion, falls aktiv
# 10.3.0.1 ist das ggü liegende tun interface
my $tm = localtime(time);
#my $tgdatei = "/home/pi/Perl/mon-all.log";
my $eldatei_upd = 0;
my $version = "2.10";

	my $cmd = "pwd";
	my $dir =`$cmd`;
	$dir = trim_cr($dir);
	my $logdatei = $dir  . "/dl3el/get-echolink.log";
	my $eldatei = $dir . "/dl3el/cl.list";
	my $qdatei = $dir . "/dl3el/get-echolink.query";
	STDOUT->autoflush(1);
#	open(LOG, ">/home/svxlink/dl3el/get-echolink.log") or die "Fehler bei Logdatei: $eldatei\n";
	open(LOG, ">$logdatei") or die "Fehler bei Logdatei: $logdatei\n";
	printf LOG "DL3EL Echolink Grabber [v$version] Start: %02d:%02d:%02d am %02d.%02d.%04d\n$0 @ARGV\n",$tm->hour, $tm->min, $tm->sec, $tm->mday, $tm->mon,$tm->year;
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
		} 
		if (substr($a,0,2) eq "p=") {
		    $srch_pref = uc(substr($a,2,length($a)));
		    print "Prefix: $srch_pref\n" if $verbose;
		}
	}
 
    print LOG "Prefix angegeben: $srch_pref\n";
    if ($srch_pref eq "") {
        open(INPUT, $qdatei) or die "Fehler bei Eingabedatei: $qdatei\n";
        local $/;#	
        $srch_pref = <INPUT>;
        close INPUT;
        print LOG "Prefix geladen: $srch_pref\n";
        $srch_pref = "DL" if ($srch_pref eq "");
        $srch_pref = "" if ($srch_pref eq "*");
        print LOG "Prefix verwendet: $srch_pref\n";
    } else {
        open(QUERY, ">$qdatei");
        print QUERY $srch_pref;
        close QUERY;
        $srch_pref = "" if ($srch_pref eq "*");
    }
# $srch_pref gibt die komplette Liste aus
    printf LOG "Par: V%s R%s S%s\n",$verbose, $reload, $srch_pref;

    read_echolink($eldatei);
    $eldatei_upd = file_date_time($eldatei);
   	printf "Zeitstempel: %s \n",$eldatei_upd if $verbose;
    create_el_table($srch_pref);
    print_el_table($eldatei_upd);
    close LOG;
    exit 0;

sub create_el_table {
my $elcall;
my $call;
my $node;
my @array;
my $cl_found = 0;
my $dlcall;
my $ssid;
my $srch_pref = $_[0];

    printf "\n\nLese Echolink: %s<br>\nVERARBEITUNG, Pref:%s\n",$el_content,$srch_pref if ($verbose >=1);

	@array = split (/<\/tr>/, $el_content);

	foreach $entry (@array) {
		$elcall = ($entry =~ /<td>([\w]+)(-R|-L|<\/td>)/i)? $1 : "undef";
		if ($elcall ne "undef") {
            $ssid = $2;
			$elcall = $elcall . $2 if (substr($2,0,1) eq "-");
			$node = ($entry =~ /(<.*>)([0-9]+)<\/td>/i)? $2 : $entry;
            $call = $elcall . $cl_found;
			if ($srch_pref eq "DL") {
                $dlcall = ($elcall =~ /(D[A-R][0-9])/i)? $1 : "undef";
            } else {
                if (substr($elcall,0,length($srch_pref)) eq $srch_pref) {
                    $dlcall = "ok";
                    printf "Search Pref: %s %s ",$srch_pref,$elcall if ($verbose >=1);
                } else {
                    $dlcall = "undef";
                }    
            }    
			if ($dlcall ne "undef") {
                printf "Call: %s, Node: %s [$elcall/$1/$ssid]\n",$call,$node;
				$CurrentLoginsTab{$call}{'CALL'} = $call;
				$CurrentLoginsTab{$call}{'ELCALL'} = $elcall;
				$CurrentLoginsTab{$call}{'ELCALLCMP'} = substr($elcall,0,length($elcall)-1);
				$CurrentLoginsTab{$call}{'ELNODE'} = $node;
				++$cl_found;
			}
		} else {
            print "." if ($verbose >= 4);
        }    
	}
	print "Prefix $srch_pref, $cl_found gefunden";
}
sub print_el_table {
my $nn = 0;
my $cl = 0;
my $pref = "";
my $pref_old = "";
    printf "<tr height=25px><th>(Script Run: $log_time) Echolink (Netz Update %s)</th></tr><tr><td>",$_[0];
    printf LOG "<tr height=25px><th>(Script Run: $log_time) Echolink (Netz Update %s)</th></tr><tr><td>",$_[0];
    print "<tr><td><form method=\"post\">";
    foreach $call (sort keys %CurrentLoginsTab) {
         ++$cl;
         $pref = substr($CurrentLoginsTab{$call}{'ELCALL'},0,2);
         if (($pref ne $pref_old) && ($pref_old ne "")) {
            print "<br>";
            print "</td></tr><tr><td>";
             $nn = 0;
         }
         $pref_old = $pref;
         printf "<button type=submit id=jmptoE name=jmptoE class=active_id value=%s>%s:%s</button>",$CurrentLoginsTab{$call}{'ELNODE'},$CurrentLoginsTab{$call}{'ELCALL'},$CurrentLoginsTab{$call}{'ELNODE'};
         printf LOG "($cl/$nn) %s/%s, ",$CurrentLoginsTab{$call}{'ELCALL'},$CurrentLoginsTab{$call}{'ELNODE'};
         ++$nn;
         if ($nn > 6) {
             print "<br>";
             print LOG "\n";
             $nn = 0;
         }    
    }    
    print "</form></td></tr>";
 }

sub file_date_time {
	my $eldatei = $_[0];
    my $tm = localtime(stat($eldatei)->mtime);
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

sub read_echolink {
    my $eldatei = $_[0];

    my $eldatei_org = stat($eldatei)->mtime;
    my $tm = time();
    my $delta = $tm-$eldatei_org;
    $delta = 500 if ($reload);
    printf "Delta: %s ($tm)",$delta if $verbose;

    $log_time = act_date_time();
    if ($delta > 300) {
		my $cmd = "wget --tries=2 --timeout=5 -O " . $eldatei . " -q http://www.echolink.org/logins.jsp";
        my $tx =`$cmd`;
        printf "$log_time aktuelle Echolink Login für DL geholt (Delta war $delta)<br>\n";
    }    

    open(INPUT, $eldatei) or die "Fehler bei Eingabedatei: $eldatei [$0: open $eldatei: $!]\n";
    {
        local $/;#	
        $el_content = <INPUT>;
        printf "$log_time Echolink Daten aus Cache geholt<br>\n" if $verbose;
    }    
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
