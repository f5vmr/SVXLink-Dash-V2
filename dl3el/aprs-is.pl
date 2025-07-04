#!/usr/bin/perl
use warnings;
use strict;
use utf8;
use Time::Piece;
use File::stat;
use IO::Socket::INET;

my $entry;
my @array;
my $login = "";
my $verbose = 0;
my $aprs = "";
my $call = "";
my $srccall = "";
my $srcdest = "";
my $destcall = "";
my $data = "";
my $rr = 0;
my $message_time = "";
my $log_time = "";
my $write2file = "";
# prüfen ob Heimnetz erreichbar ist, keine Aktion, falls aktiv
# 10.3.0.1 ist das ggü liegende tun interface
my $tm = localtime(time);
my $version = "1.10";
my $dir = "";
my $conf = "";

my $aprs_login  = "";
my $aprs_passwd = "";
my $aprs_msg_call = "";

my $hispaddr = "";

# flush after every write
$| = 1;

my ($socket,$client_socket);


	my $total = $#ARGV + 1;
	my $counter = 1;
	foreach my $a(@ARGV) {
		print "Arg # $counter : $a\n" if ($verbose == 7);
		$counter++;
		if (substr($a,0,2) eq "v=") {
		    $verbose = substr($a,2,1);
		    print "Debug On, Level: $verbose\n" if $verbose;
		} 
		if (substr($a,0,2) eq "l=") {
		    $login = substr($a,2,length($a-2));
		    print "login: $login\n" if $verbose;
        }
		if (substr($a,0,2) eq "d=") {
		    $dir = substr($a,2,length($a-2));
		    $ dir = $dir . "/dl3el";
		    print "dir: $dir\n" if $verbose;
        }
	}
# best to create a crontab entry for the receiver to start at system boot
# @reboot /var/www/html/dl3el/aprs-is.pl
# "/var/www/html" should be replaced with the running directroy of the svxlink dasboard
# all necessary definitgion have to made in the file /var/www/html/dl3el/aprs-is.conf

	$dir = ($0 =~ /(.*)aprs-is.pl/i)? $1 : "undef";
	$conf = $dir . "aprs-is.conf";
	printf "DL3EL APRS-IS Message Receiver [v$version] Start: %02d:%02d:%02d am %02d.%02d.%04d\n$0 @ARGV $dir $conf\n",$tm->hour, $tm->min, $tm->sec, $tm->mday, $tm->mon,$tm->year if ($verbose >= 1);
	read_config($conf);
 	my $logdatei = $dir  . "/aprs-is.log";
 	my $msgdatei = $dir  . "/aprs-is.msg";
    printf "LOG: %s Logdatei: %s\n",$dir,$logdatei if ($verbose >= 1);
    printf "MSG: %s Logdatei: %s\n",$dir,$msgdatei if ($verbose >= 1);
	STDOUT->autoflush(1);
#    open(LOG, ">/home/svxlink/dl3el/get-monitor.log") or die "Fehler bei Logdatei: $tgdatei\n";
	open(LOG, ">$logdatei") or die "Fehler bei Logdatei: $logdatei\n";
	open(MSG, ">>$msgdatei") or die "Fehler bei LOGGINGdatei: $msgdatei\n";
    printf LOG "LOG: %s LOGGINGdatei: %s\n",$dir,$logdatei if ($verbose >= 1);
    printf LOG "MSG: %s Logdatei: %s\n",$dir,$msgdatei if ($verbose >= 1);
	printf LOG "DL3EL APRS-IS Message Receiver [v$version] Start: %02d:%02d:%02d am %02d.%02d.%04d\n$0 @ARGV\n",$tm->hour, $tm->min, $tm->sec, $tm->mday, $tm->mon,$tm->year;
	printf MSG "DL3EL APRS-IS Message Receiver [v$version] Start: %02d:%02d:%02d am %02d.%02d.%04d\n",$tm->hour, $tm->min, $tm->sec, $tm->mday, $tm->mon,$tm->year;
    close MSG;
    close LOG;

Start:

# creating object interface of IO::Socket::INET modules which internally creates
# socket, binds and connects to the TCP server running on the specific port.
	$socket = new IO::Socket::INET (
	PeerHost => 'euro.aprs2.net',
	PeerPort => '14580',
	Proto => 'tcp',
	) or do {
		$write2file = sprintf "[$log_time] ERROR in Socket Creation : $!\n";
		print_file($logdatei,$write2file);
		die "ERROR in Socket Creation : $!\n";
	};
    close LOG;

	$log_time = act_time();
#	print LOG "[$log_time] TCP Connection Success.\n";
    $write2file = sprintf "[$log_time] TCP Connection Success.\n";
    print_file($logdatei,$write2file) if ($verbose >= 0);

# read the socket data sent by server.
	$data = <$socket>;
# we can also read from socket through recv()  in IO::Socket::INET
# $socket->recv($data,1024);
	++$rr;
	$log_time = act_time();
#	print LOG "[$log_time] Received from Server ($rr): $data\n";
    $write2file = sprintf "[$log_time] Received from Server ($rr): $data\n";
    print_file($logdatei,$write2file);

# write on the socket to server.
	if ($login eq "") {
		$login = sprintf ("user %s pass %s vers dl3el_pos 0.1 filter b/%s",$aprs_login,$aprs_passwd,$aprs_msg_call);
	}	
	$data = $login;
	$log_time = act_time();
#	print LOG "[$log_time] $data\n";
    $write2file = sprintf "[$log_time] $data\n";
    print_file($logdatei,$write2file) if ($verbose >= 0);
	print $socket "$data\n";
# we can also send the data through IO::Socket::INET module,
# $socket->send($data);
	while (1) {
	    my $datastring = '';
	    $hispaddr = recv($socket, $datastring, 512, 0); # blocking recv
	    if (!defined($hispaddr)) {
			$log_time = act_time();
			print("recv failed: $!\n") if ($verbose >= 1);
#			print LOG ("[$log_time] recv failed: $!\n");
			$write2file = sprintf "[$log_time] recv failed: $!\n";
			print_file($logdatei,$write2file) if ($verbose >= 1);
	    } else {
			++$rr;
			$log_time = act_time();
#			print LOG ("[$log_time] recv successful ($datastring), $rr\n");
			print("recv successful ($datastring), $rr\n") if ($verbose >= 1);
			$write2file = sprintf "[$log_time] recv successful ($datastring), $rr\n";
# recv successful (# logresp DL3EL-AI verified, server T2PRT
			my $payload = ($datastring =~ /(\# logresp)/i)? $1 : "undef";
			if ($payload ne "undef") {
				print_file($logdatei,$datastring);
			} else {	
				print_file($logdatei,$write2file) if ($verbose >= 1);
				parse_aprs($datastring);
			}	
	    }
	}	
	$socket->close();

sub parse_aprs {
	my $raw_data = trim_cr($_[0]);
#	print LOG "working on: [$raw_data]\n" if ($verbose >= 1);
	$write2file = sprintf "working on: [$raw_data]\n" if ($verbose >= 1);
	print_file($logdatei,$write2file) if ($verbose >= 1);
	my $payload = ($raw_data =~ /([\w-]+)\>([\w-]+)\,.*::([\w-]+)[ :]+(.*)\{([\d]+)/i)? $4 : "undef";
	if ($payload ne "undef") {
# ack first
		send_ack($1,$2,$3);
# then process		
		$message_time = act_time();
#		printf LOG "[$message_time] Call: %s from %s, Message: %s, Number: %d\n",$1,$payload,$3;
		$write2file = sprintf "[$message_time] Call: %s from: %s, Message: %s, Number: %d\n",$3,$1,$payload,$5;
		print_file($logdatei,$write2file) if ($verbose >= 1);
#		printf MSG "[$message_time] Call: %s, Message: %s\n",$1,$payload;
		$write2file = sprintf "[$message_time] Message to %s from %s: %s (%s)\n",$3,$1,$payload,$5;
		print_file($msgdatei,$write2file);
	}
}

sub send_ack {
	my $srccall = $_[0];
	my $srcdest = $_[1];
	my $destcall = $_[2];
		while (length($srccall) < 9) {
			$srccall = $srccall . " ";
		}	
# DL9SAU: answer_message = Tcall + ">" + MY_APRS_DEST_IDENTIFYER + "::" + String(msg_from) + ":ack" + String(q+1);
		$data = sprintf ("%s>%s,WIDE1-1::%s:ack%s\n",$destcall,$srcdest,$srccall,$5);
#		print $socket "$data\n";
# we can also send the data through IO::Socket::INET module,
		$socket->send($data);
		$log_time = act_time();
#		print LOG "[$log_time] $data";
		$write2file = sprintf "[$log_time] $data";
		print_file($logdatei,$write2file) if ($verbose >= 1);
}

sub print_file {
	my $file = $_[0];
	my $data = $_[1];
	
	open(FILE, ">>$file") or die "Fehler bei Logdatei: $file\n";
	print FILE $data;
	print $data if ($verbose >= 1);
    close FILE;
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
	$string =~ s/\r//g;
	return $string;
}

sub act_time {
	$tm = localtime(time);
	return (sprintf("%02d.%02d.%04d %02d:%02d:%02d",$tm->mday, $tm->mon,$tm->year,$tm->hour, $tm->min, $tm->sec));
}
sub read_config {
	$log_time = act_time();
	print "[$log_time] reading config...\n" if ($verbose >= 1);
	my $confdatei = $_[0];
	my $par;
	open(INPUT, $confdatei) or die "Fehler bei Eingabedatei: $confdatei\n";
	    {
	    local $/;#	
	    $data = <INPUT>;
	    }    
	close INPUT;
	print "Datei $confdatei erfolgreich geöffnet\n" if ($verbose >= 1);
	@array = split (/\n/, $data);
	$aprs_login  = "NOCALL-AI";
	$aprs_passwd = "-1";
	$aprs_msg_call = "NOCALL";
	
	foreach $entry (@array) {
		if ((substr($entry,0,1) ne "#") && (substr($entry,0,1) ne "")) {
			printf "[%s] \n",$entry if ($verbose >= 1);
			$par = ($entry =~ /([\w]+).*\=.*\"(.*)\"/s)? $2 : "undef";
			$aprs_login = $par if ($1 eq "aprs_login");
			$aprs_passwd = $par if ($1 eq "aprs_passwd");
			$aprs_msg_call = $par if ($1 eq "aprs_msg_call");
		}
	}
	print "config received:\n" if ($verbose >= 1);
	printf "aprs_login:%s\naprs_passwd:%s\naprs_msg_call:%s\n", $aprs_login,$aprs_passwd,$aprs_msg_call if ($verbose >= 1);
}
