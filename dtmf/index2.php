<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">


  <head>
    <meta charset="UTF-8">
    <title>Audio Peak Meter</title>
    <meta name="Author" content="Waldek SP2ONG" />
    <meta name="Description" content="Audio Test Peak Meter for SVXLink by SP2ONG 2022" />
    <meta name="KeyWords" content="SVXLink, SVXRelector,SP2ONG" />
    <link href="/css/css.php" type="text/css" rel="stylesheet" />
<style type="text/css">
body {
  background-color: #eee;
  font-size: 18px;
  font-family: Arial;
  font-weight: 300;
  margin: 2em auto;
  max-width: 40em;
  line-height: 1.5;
  color: #444;
  padding: 0 0.5em;
}
h1, h2, h3 {
  line-height: 1.2;
}
a {
  color: #607d8b;
}
.highlighter-rouge {
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: .2em;
  font-size: .8em;
  overflow-x: auto;
  padding: .2em .4em;
}
pre {
  margin: 0;
  padding: .6em;
  overflow-x: auto;
}

#player {
    position:relative;
    width:205px;
    overflow: hidden;
    direction: ltl;
}


.button {
  border: none;
  color: #454545;
  padding: 16px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  transition-duration: 0.4s;
  cursor: pointer;
}
.buttonh {
  background-image: linear-gradient(to bottom, #337ab7 0%, #265a88 100%);color:#454545;
  color: #454545;
}

.buttonh:hover {
  background-color: #4CAF50;
  color: #454545;
}
.green
{
  background-color: #448f47;
  border: none;
  color: white;
  font-weight: 600;
  font-size: 13px;
  padding: 4px 12px;
  text-decoration: none;
  margin: 4px 4px;
  cursor: pointer;
  border-radius: 4px;

}

.blue
{
  background-image: linear-gradient(to bottom, #337ab7 0%, #265a88 100%);color:#454545;
  border: none;
  color: white;
  font-weight: 600;
  font-size: 16px;
  padding: 4px 12px;
  text-decoration: none;
  margin: 4px 4px;
  cursor: pointer;
  border-radius: 4px;
  height:80px;
  width:150px;
}

.red
{
  background-color: #b00;
  border: none;
  color: white;
  font-weight: 600;
  font-size: 13px;
  padding: 4px 12px;
  text-decoration: none;
  margin: 4px 4px;
  cursor: pointer;
  border-radius: 4px;
}
.orange
{
  background-color: DarkOrange;
  border: none;
  color: white;
  font-weight: 600;
  font-size: 13px;
  padding: 4px 12px;
  text-decoration: none;
  margin: 4px 4px;
  cursor: pointer;
  border-radius: 4px;
}
.purple
{
  background-color: #800080;
  border: none;
  color: white;
  font-weight: 600;
  font-size: 13px;
  padding: 4px 12px;
  text-decoration: none;
  margin: 4px 4px;
  cursor: pointer;
  border-radius: 4px;
}

</style>
</head>

<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<center>
<fieldset style="border:#3083b8 2px groove;box-shadow:0 0 10px #999; background-color:#f1f1f1; width:500px;margin-top:15px;margin-left:0px;margin-right$<div style="padding:0px;width:495px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px$<center>
<h1 id="web-audio-peak-meters" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">DTMF Keyboard</h1>
<!-- <p style="font-size:8px;color:#454545;font-weight:bold;">Please don't use too many buttons at once.</span></p>
-->
<?php
function cidr_match($ip, $cidr) {
    $outcome = false;
    $pattern = '/^(([01]?\d?\d|2[0-4]\d|25[0-5])\.){3}([01]?\d?\d|2[0-4]\d|25[0-5])\/(\d{1}|[0-2]{1}\d{1}|3[0-2])$/';
    if (preg_match($pattern, $cidr)){
        list($subnet, $mask) = explode('/', $cidr);
        if (ip2long($ip) >> (32 - $mask) == ip2long($subnet) >> (32 - $mask)) {
            $outcome = true;
        }
    }
    return $outcome;
}

    $url1=$_SERVER['REQUEST_URI'];
//    header("Refresh: 10; URL=$url1");


$ip = isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']; 
$net1= cidr_match($ip,"192.168.0.0/16");
$net2= cidr_match($ip,"172.16.0.0/12");
$net3= cidr_match($ip,"127.0.0.0/8");
$net4= cidr_match($ip,"10.0.0.0/8");
if ($net1 == TRUE || $net2 == TRUE || $net3 == TRUE || $net4 == TRUE) {



// Defined buttons:

    if (isset($_POST['button1']))
    {
        shell_exec('echo "*91260#" > /tmp/dtmf_svx');
    }

   if (isset($_POST['button2']))
    {
        shell_exec('echo "*912600#" > /tmp/dtmf_svx');
    }

 if (isset($_POST['button3']))
    {
        shell_exec('echo "*9126078#" > /tmp/dtmf_svx');
    }

 if (isset($_POST['button4']))
    {
        shell_exec('echo "*91999#" > /tmp/dtmf_svx');
    }

 if (isset($_POST['button5']))
    {
        shell_exec('echo "9191#" > /tmp/dtmf_svx');
    }

 if (isset($_POST['button6']))
    {
        shell_exec('echo "9126021#" > /tmp/dtmf_svx');
    }

 if (isset($_POST['button7']))
    {
        shell_exec('echo "9126062#" > /tmp/dtmf_svx');
    }

 if (isset($_POST['button8']))
    {
        shell_exec('echo "910#" > /tmp/dtmf_svx');
    }

if (isset($_POST['button9']))
    {
        shell_exec('echo "*#" > /tmp/dtmf_svx');
    }

 if (isset($_POST['button10']))
    {
        shell_exec('echo "*1#" > /tmp/dtmf_svx');
    }

 if (isset($_POST['button11']))
    {
        shell_exec('echo "*2# 453582#" > /tmp/dtmf_svx');
    }

 if (isset($_POST['button12']))
    {
        shell_exec('echo "2# 871657## " > /tmp/dtmf_svx');
    }
 if (isset($_POST['button13']))
    {
        shell_exec('echo "2# 871657#" > /tmp/dtmf_svx');
    }


// Keyboard
 if (isset($_POST['button20']))
    {
        shell_exec('echo "0" > /tmp/dtmf_svx');
      //  echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: 0</center></h1></p></pre>';
    }

 if (isset($_POST['button21']))
    {
        shell_exec('echo "1" > /tmp/dtmf_svx');
     //  echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: 1</center></h1></p></pre>';
    }

 if (isset($_POST['button22']))
    {
        shell_exec('echo "2" > /tmp/dtmf_svx');
      //  echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: 2</center></h1></p></pre>';
    }

 if (isset($_POST['button23']))
    {
        shell_exec('echo "3" > /tmp/dtmf_svx');
       // echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: 3</center></h1></p></pre>';
    }

 if (isset($_POST['button24']))
    {
        shell_exec('echo "4" > /tmp/dtmf_svx');
       // echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: 4</center></h1></p></pre>';
    }

 if (isset($_POST['button25']))
    {
        shell_exec('echo "5" > /tmp/dtmf_svx');
       // echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: 5</center></h1></p></pre>';
    }

 if (isset($_POST['button26']))
    {
        shell_exec('echo "6" > /tmp/dtmf_svx');
       // echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: 6</center></h1></p></pre>';
    }

 if (isset($_POST['button27']))
    {
        shell_exec('echo "7" > /tmp/dtmf_svx');
      //  echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: 7</center></h1></p></pre>';
    }

 if (isset($_POST['button28']))
    {
        shell_exec('echo "8" > /tmp/dtmf_svx');
    //    echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: 8</center></h1></p></pre>';
    }

 if (isset($_POST['button29']))
    {
        shell_exec('echo "9" > /tmp/dtmf_svx');
    //    echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: 9</center></h1></p></pre>';
    }

 if (isset($_POST['button30']))
    {
        shell_exec('echo "*" > /tmp/dtmf_svx');
       // echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: *</center></h1></p></pre>';
    }

if (isset($_POST['button31']))
    {
        shell_exec('echo "#" > /tmp/dtmf_svx');
     //   echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: #</center></h1></p></pre>';
    }

if (isset($_POST['buttonAA']))
    {
        shell_exec('echo "A" > /tmp/dtmf_svx');
     //   echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: #</center></h1></p></pre$    }
}
if (isset($_POST['buttonBB']))
    {
        shell_exec('echo "B" > /tmp/dtmf_svx');
     //   echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: #</center></h1></p></pre$    }
}

if (isset($_POST['buttonCC']))
    {
        shell_exec('echo "C" > /tmp/dtmf_svx');
     //   echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: #</center></h1></p></pre$    }
}

if (isset($_POST['buttonDD']))
    {
        shell_exec('echo "D" > /tmp/dtmf_svx');
     //   echo '<pre><h1><center><p style="color: #454545; ">Send DTMF: #</center></h1></p></pre$    }
}

// POWER OFF
if (isset($_POST['button99']))
    {
        shell_exec('sudo poweroff');
   //     echo '<pre><h1><center><p style="color: #454545; ">POWER OFF</center></h1></p></pre>';
    }
?>
<form method="post">
    <p>
         <center><button style="height: 60px; width: 100px;font-size:25px;" button name="button21">1</button><button style="height: 60px; width: 100px;font-size:25px;" button name="button22">2</button><button style="height: 60px; width: 100px;font-size:25px;" button name="button23">3</button><button style="height: 60px; width: 100px;font-size:25px;" button name="buttonA">A</button></center>
         <center><button style="height: 60px; width: 100px;font-size:25px;" button name="button24">4</button><button style="height: 60px; width: 100px;font-size:25px;" button name="button25">5</button><button style="height: 60px; width: 100px;font-size:25px;" button name="button26">6</button><button style="height: 60px; width: 100px;font-size:25px;" button name="buttonBB">B</button></center>
         <center><button style="height: 60px; width: 100px;font-size:25px;" button name="button27">7</button><button style="height: 60px; width: 100px;font-size:25px;" button name="button28">8</button><button style="height: 60px; width: 100px;font-size:25px;" button name="button29">9</button><button style="height: 60px; width: 100px;font-size:25px;" button name="buttonCC">C</button></center>
         <center><button style="height: 60px; width: 100px;font-size:25px;" button name="button30">*</button><button style="height: 60px; width: 100px;font-size:25px;" button name="button20">0</button><button style="height: 60px; width: 100px;font-size:25px;" button name="button31">#</button><button style="height: 60px; width: 100px;font-size:25px;" button name="buttonDD">D</button></center>
    </p>
    </form>
  

<?php
} else {

//header("Location: http://svxlink.pl:888");
//exit();
}
?>
</fieldset>
</body>
</html>
