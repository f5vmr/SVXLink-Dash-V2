#!/usr/bin/perl
use v5.28;

use Time::Piece;

my $verbose = 0;
my $cmd = "";
my $data = "";

    my $log_time = act_time();

	my $cmd = "pwd";
	my $dir =`$cmd`;
	my $dirr = trim_cr($dir);
    $dir = ($dirr =~ /(.*)\/include/s)? $1 : "undef";

    if ($dir eq "undef") {
        $dir = $dirr;
    }    

    print "[$log_time] DIR $dir ($dirr)\n" if ($verbose >= 0);
	my $shari = $dir  . "/dl3el/sa818";
    print "[$log_time] SHARI $shari\n" if ($verbose >= 0);

    $cmd = sprintf("python3 %s/sa818-running.py -q",$shari);
    print "[$log_time] [$cmd]\n" if ($verbose >= 1);
	$cmd = qx($cmd);
    print "[$log_time] [$cmd]\n" if ($verbose >= 1);
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
