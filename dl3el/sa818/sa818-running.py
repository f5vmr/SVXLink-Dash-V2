#!/usr/bin/env python3

import serial
import sys
import time
import signal

# serport = '/dev/ttyUSB0'
# serport = '/dev/ttyUSB1'
serport = '/dev/ttyUSB.shari'
baud = '9600'
channelspace = '1'      # 0=12.5kHz, 1=25kHz
rxfreq = '430.3750'     # TX frequency
rxfreq1 = '430.3750'     # TX frequency
rxfreq2 = '433.2000'     # TX frequency
rxfreq2 = '430.5750'     # TX frequency
rxfreq2 = '431.9750'     # TX frequency
txfreq = rxfreq         # Same as rx freq - we work simplex
squelch = '1'           # 0-8 (0 = open)
txcxcss = '0000'        # CTCSS 77Hz
rxcxcss = '0000'        # CTCSS 77Hz
# txcxcss = rxcxcss
#txcxcss = '023N'        # CTCSS / CDCSS TX
#rxcxcss = '023N'        # CTCSS / CDCSS RX
flataudio = '1'           # switch to discriminator output and input if value = 1
bypass_lowpass = '1'      # bypass lowpass-filter if value = 1
bypass_highpass = '1'     # bypass highpass-filter if value = 1
volume = '7'              # betweeen 0..8
CRLF='\r\n'

def writeSerial(ser, string):
	ser.write((string + CRLF).encode())
	time.sleep(1.00)
	raw_serial = ser.readline()
	return raw_serial[:-2].decode()


### MAIN PROGRAM STATEMENTS ###

ser = serial.Serial(serport, baud, timeout=2)

if '-v' in sys.argv:
#	print('Opening port: ' + ser.name)
#	print ('Connecting...')
	ser.write(b'AT+DMOCONNECT\r\n')
	output = ser.readline()
#	print (output.decode("utf-8"))
	# shw current data
	ser.write(b'AT+VERSION\r\n')
	output = ser.readline()
	print (output.decode("utf-8"))
	ser.write(b'AT+DMOGETVOLUME\r\n')
	print (output.decode("utf-8"))
#	print ("Quit")
	quit()

print('Opening port: ' + ser.name)
print ('Connecting...')
ser.write(b'AT+DMOCONNECT\r\n')
output = ser.readline()
print (output.decode("utf-8"))
# shw current data
ser.write(b'AT+VERSION\r\n')
output = ser.readline()
print (output.decode("utf-8"))
ser.write(b'AT+DMOREADGROUP\r\n')
output = ser.readline()
print (output.decode("utf-8"))

# Go into loop that outputs radio signal strength. Invoke this by using SA818-prog -r
number_of_args = len(sys.argv) -1
# print ("ARGS:" + str(number_of_args))
if '-q' in sys.argv:
	print ("Quit")
	quit()

if '-r' in sys.argv:
	print ("Monitoring Radio Signal Strength (press Ctl+c to exit)")
	while True:
		response = writeSerial(ser,"RSSI?")
		print (response)
	quit()

if number_of_args == 4:
	print ("ARGS:" + str(number_of_args))
	
	if '-f' in sys.argv:
		rxfreq = sys.argv[2]         # Same as rx freq - we work simplex
		print ("qrg:" + rxfreq)
		txfreq = rxfreq         # Same as rx freq - we work simplex

	if '-c' in sys.argv:
		txcxcss = '0000'        # CTCSS 71.9Hz
		rxcxcss = sys.argv[4]   # CTCSS 71.9Hz
		squelch = '1'           # 0-8 (0 = open)
		print ("ctcss:" + rxcxcss)
else: 
	print ("zu wenig Parametere: " + str(number_of_args))
	quit()

print ('Configuring radio...')
config = 'AT+DMOSETGROUP={},{},{},{},{},{}\r\n'.format(channelspace, txfreq, rxfreq, txcxcss, squelch, rxcxcss)
print (config)
ser.write(config.encode())
output = ser.readline()
print ('reply: ' + output.decode("utf-8"))

print ('Set filter...')
config = 'AT+SETFILTER={},{},{}\r\n'.format(flataudio, bypass_highpass, bypass_lowpass)
print(config)
ser.write(config.encode())
output = ser.readline()
print ('reply: ' + output.decode("utf-8"))

print ('Setting volume...')
config = 'AT+DMOSETVOLUME={}\r\n'.format(volume)
print(config)
ser.write(config.encode())
output = ser.readline()
print ('reply: ' + output.decode("utf-8"))

print ('Setting emission tail tone...')
ser.write(b'AT+SETTAIL=0\r\n')
output = ser.readline()
print ('reply: ' + output.decode("utf-8"))

print ('Getting Module Version...')
ser.write(b'AT+VERSION\r\n')
output = ser.readline()
print ('reply: ' + output.decode("utf-8"))

print ('Getting Settings...')
ser.write(b'AT+DMOREADGROUP\r\n')
output = ser.readline()
print ('reply: ' + output.decode("utf-8"))

