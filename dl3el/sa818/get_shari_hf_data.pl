#!/usr/bin/perl
use v5.28;

use Time::Piece;

my $verbose = 0;
my $cmd = "";
my $data = "";
my $shari = "";
my $rssi = "";
my $txctcss = "";
my $rxctcss = "";
my @CTCSS = (
  "None", "67.0", "71.9", "74.4", "77.0", "79.7", "82.5", "85.4", "88.5",
  "91.5", "94.8", "97.4", "100.0", "103.5", "107.2", "110.9", "114.8", "118.8",
  "123.0", "127.3", "131.8", "136.5", "141.3", "146.2", "151.4", "156.7",
  "162.2", "167.9", "173.8", "179.9", "186.2", "192.8", "203.5", "210.7",
  "218.1", "225.7", "233.6", "241.8", "250.3"
);

    my $log_time = act_time();

    my $cmd = "pwd";
    my $dir =`$cmd`;
    my $dirr = trim_cr($dir);
    $dir = ($dirr =~ /(.*)\/include/s)? $1 : "undef";

    if ($dir eq "undef") {
        $dir = $dirr;
    }    

    print "[$log_time] DIR $dir ($dirr)\n" if ($verbose >= 0);
    $shari = $dir  . "/dl3el/sa818";
    print "[$log_time] SHARI $shari\n" if ($verbose >= 0);

    $cmd = sprintf("python3 %s/sa818-running.py -q",$shari);
    print "[$log_time] [$cmd]\n" if ($verbose >= 1);
    $cmd = qx($cmd);
    print "[$log_time] [$cmd]\n" if ($verbose >= 1);
# RSSI=020
    $rssi = ($cmd =~ /.*RSSI=([\d]+)/s)? $1 : "undef";
# +DMOREADGROUP:1,430.5750,430.5750,0000,1,0002
    $data = ($cmd =~ /.*\+DMOREADGROUP:(\d),([\d-|\.]+),([\d-|\.]+),([\w]+),(\d),([\w]+)/s)? $1 : "undef";
    $txctcss = $CTCSS[$4];
    $txctcss = $txctcss . "Hz" if ($4 ne "0000");
    $rxctcss = $CTCSS[$6];
    $rxctcss = $rxctcss . "Hz" if ($6 ne "0000");
    printf "%s / RSSI:%s<br>RX:%s/TX:%s",$2,$rssi,$rxctcss,$txctcss;
    if (!$verbose) {
	exit 0;
    } else {
	print "\n$2\n" if ($verbose >= 0);
	print "Channelspace: [$1]\n" if ($verbose >= 1);
	print "QRG: [$2]\n" if ($verbose >= 0);
	print "QRG_In: [$3]\n" if ($verbose >= 1);
	printf "TXCTCSS: [%shz]\n", $txctcss;
	print "Squelch: [$5]\n" if ($verbose >= 0);
	printf "RXCTCSS: [%shz]\n", $rxctcss;
	printf "CMD: %s\n",$cmd;
	$data = ($cmd =~ /.*RSSI=([\d]+)/s)? $1 : "undef";
	printf "RSSI: %s\n",$data;
    }

sub trim_cr {
	my $string = $_[0];
	$string = shift;
	$string =~ s/\n//g;
	return $string;
}

sub act_time {
	my $tm = localtime(time);
	return (sprintf("%02d:%02d:%02d",$tm->hour, $tm->min, $tm->sec));
}


