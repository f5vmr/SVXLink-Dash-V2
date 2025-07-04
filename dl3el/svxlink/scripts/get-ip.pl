#!/usr/bin/perl
use warnings;
use strict;
use utf8;
use Time::Piece;
use File::stat;

my $entry;
my @array;
my $ip_raw = "";
my $ip = "";
my $nn = 0;

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
	my $ipdatei = $dir  . "/dl3el/ip.txt";
	STDOUT->autoflush(1);
	my $total = $#ARGV + 1;
	my $counter = 1;
	foreach my $a(@ARGV) {
	    print "Arg # $counter : $a\n" if ($verbose == 7);
	    $counter++;
	    if (substr($a,0,2) eq "v=") {
		$verbose = substr($a,2,1);
		print "Debug On, Level: $verbose\n" if $verbose;
	    } 
	}
 
	$cmd = "hostname -I";
	$ip_raw =`$cmd`;
	print "Ip Adressen: $ip_raw\n" if ($verbose >0);
	my $speak_all_ip = "";
	@array = split (/ /, $ip_raw);
	foreach $entry (@array) {
# do not pread IPv6 addresses
	    if ((length($entry) > 7) && (length($entry) < 16)) {
		if ($nn) {
		    $speak_all_ip = $speak_all_ip . "CW::play \"\/\";"
		}    
		print "Entry($nn) [$entry]\n" if ($verbose >0);
		$speak_all_ip = $speak_all_ip . prepare_speak_ip($entry);
		++$nn;
	    }
	}
	$speak_all_ip = $speak_all_ip . "CW::play \"SK\";";
	print "Total: $nn\n" if ($verbose >0);
#	$speak_all_ip = sprintf "playNumber \"192.168\";playMsg \"MetarInfo\" \"decimal\";playNumber \"241.51\";";
	print "$speak_all_ip\n";    
    exit 0;

sub prepare_speak_ip {
    my $ip_string = $_[0];
    my $ip1;
    my $ip2;
    my $ip3;
    my $ip4;
    my $ip_speak;
    my $entry;
    my @array;
#IPF=$(echo $IP | sed 's/ /\";CW::play \"\/\";spellWord \"/g')
# sudo echo "spellWord \""$IPF"\";playSilence 100;CW::play \"SK\" ;" >/tmp/ipaddr1.tcl
# playNumber 
#spellWord "192.168.241.67";CW::play "/";spellWord "192.168.241.68";CW::play "/";spellWord "192.168.234.2";CW::play "/";spellWord "192.168.231.1";CW::play "/";spellWord "172.17.0.1";CW::play "/";spellWord "172.19.0.1";playSilence 100;CW::play "SK" ;
    print "Ip Adresse: $ip_string\n" if $verbose;;
    ($ip1, $ip2, $ip3, $ip4) = split (/\./,$ip_string);
    print "Ip Adresse: $ip1, $ip2, $ip3, $ip4\n" if $verbose;
    my $decimal = "playMsg \"MetarInfo\" \"decimal\"";
#    $ip_speak = sprintf "playNumber \"%s.%s\";spellWord \"\,\";playNumber \"%s.%s\";",$ip1, $ip2, $ip3, $ip4;
    $ip_speak = sprintf "playNumber \"%s\";%s;playNumber \"%s\";%s;playNumber \"%s\";%s;playNumber \"%s\";",$ip1, $decimal, $ip2, $decimal, $ip3, $decimal, $ip4;
    print "$ip_speak\n" if $verbose;;
    return $ip_speak;
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
