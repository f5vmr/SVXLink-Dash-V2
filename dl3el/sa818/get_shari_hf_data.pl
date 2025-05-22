#!/usr/bin/perl
use v5.28;

my $verbose = 0;
my $cmd = "";
my $data = "";


#    $cmd = sprintf("sudo python3 /home/pi/SVXLink/shari/sa818-running.py -q");
    $cmd = sprintf("python3 /home/svxlink/sa818-running.py -q");
    print "[$cmd]\n" if ($verbose >= 1);
#    `$cmd`;
	$cmd = qx($cmd);
    print "[$cmd]\n" if ($verbose >= 1);
# +DMOREADGROUP:1,430.5750,430.5750,0000,1,0002
    $data = ($cmd =~ /.*\+DMOREADGROUP:(\d),([\d-|\.]+),([\d-|\.]+),([\w]+),(\d),([\w]+)/s)? $1 : "undef";
    printf "%s / %s",$2,$6;
exit 0;
    print "$2" if ($verbose >= 0);
    print "Channelspace: [$1]\n" if ($verbose >= 1);
    print "QRG: [$2]\n" if ($verbose >= 0);
    print "QRG_In: [$3]\n" if ($verbose >= 1);
    print "TXCTCSS: [$4]\n" if ($verbose >= 0);
    print "Squelch: [$5]\n" if ($verbose >= 0);
    print "RXCTCSS: [$6]\n" if ($verbose >= 0);
