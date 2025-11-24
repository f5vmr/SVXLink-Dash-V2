<?php
header("Content-type: text/css");

    $backgroundPage = "edf0f5";
    $backgroundContent = "f1f1f1";
    $backgroundBanners = "3083b8";
    $textBanners = "ffffff";
    $bannerDropShaddows = "303030";
    $tableHeadDropShaddow = "8b0000";
    $textContent = "000000";
    $tableRowEvenBg = "f7f7f7";
    $tableRowOddBg = "e0e0e0";
?>
.container {
    width: 900px;
    text-align: left;
    background : #f1f1f1;
    margin: auto;
}

body, font {
    font: 12px verdana,arial,sans-serif;
    color: #ffffff;
    -webkit-text-size-adjust: none;
    -moz-text-size-adjust: none;
    -ms-text-size-adjust: none;
    text-size-adjust: none;
}

.header {
    background : #<?php echo $backgroundBanners; ?>;
    text-decoration : none;
    color : #<?php echo $textBanners; ?>;
    font-family : verdana, arial, sans-serif;
    text-align : left;
    padding : 5px 0px 5px 0px;
    border-radius : 10px 10px 10px 10px;
 }

.nav {
    float : left;
    margin : 0;
    padding : 3px 3px 3px 3px;
    width : 185px;
    background : #f1f1f1;
    font-weight : normal;
    min-height : 100%;
}

.content {
    margin : 0 0 0 0px;
    padding : 1px 5px 5px 5px;
    color : #<?php echo $textContent; ?>;
    background : #<?php echo $backgroundContent; ?>;
    text-align: center;
}
.content2 {
    margin : 0 0 0 0px;
    padding : 1px 5px 5px 5px;
    color : #<?php echo $textContent; ?>;
    background : #<?php echo $backgroundContent; ?>;
    text-align: center;
}
.contentwide {
    padding: 5px 5px 5px 5px;
    color: #<?php echo $textContent; ?>;
    background: #<?php echo $backgroundContent; ?>;
    text-align: center;
}

.footer {
    background : #<?php echo $backgroundBanners; ?>;
    text-decoration : none;
    color : #<?php echo $textBanners; ?>;
    font-family : verdana, arial, sans-serif;
    font-size : 9px;
    text-align : center;
    padding : 10px 0 10px 0;
    border-radius : 0 0 10px 10px;
    clear : both;
}

#tail {
    height: 450px;
    width: 805px;
    overflow-y: scroll;
    overflow-x: scroll;
    color: #00ff00;
    background: #000000;
}

table {
    vertical-align: middle;
    text-align: center;
    empty-cells: show;
    padding-left: 0px;
    padding-right: 0px;
    padding-top: 0px;
    padding-bottom: 0px;
    border-collapse:collapse;
    border-color: #000000;
    border-style: solid;
    border-spacing: 4px;
    border-width: 2px;
    text-decoration: none;
    color: #ffffff;
    background: #000000;
    font-family: verdana,arial,sans-serif;
    width: 100%;
    white-space: nowrap;
}

table.linki {
border: none;
}
table.linki td {
border: none; 
font-size:12px; 
color:white; 
font-weight: bold;
background-color: #045fb4;
}

table.linki a:link {
color: yellow;
font-weight: bold;
}
table.linki a:visited {
color: yellow;
font-weight: bold;
}
table.linki a:hover {
font-weight: bold;
color: orange;
}

span.links a:link {
color: #ffe63b;
font-weight: bold;
}
span.links a:visited {
color: #ffe63b;
font-weight: bold;
}
span.links a:hover {
font-weight: bold;
color: orange;
}

table th {
    font-family: "Lucidia Console",Monaco,monospace;
    text-shadow: 1px 1px #<?php echo $tableHeadDropShaddow; ?>;
    text-decoration: none;
    background: #<?php echo $backgroundBanners; ?>;
    border: 1px solid #c0c0c0;
}

table tr:nth-child(even) {
    background: #<?php echo $tableRowEvenBg; ?>;
}

table tr:nth-child(odd) {
    background: #<?php echo $tableRowOddBg; ?>;
}

table td {
    color: #000000;
    font-family: "Lucidia Console",Monaco,monospace;
    text-decoration: none;
    border: 1px solid #000000;
    overflow-x: hidden;
}
table td.links {
    color: #ff0000;
    font-family: "Lucidia Console",Monaco,monospace;
    text-decoration: none;
    border: 0px solid #000000;
    overflow-x: hidden;
}


body {
    background: #<?php echo $backgroundPage; ?>;
    color: #000000;
}

a {
    text-decoration:none;
    
}

a:link, a:visited {
    text-decoration: none;
    color: #0065ff;
    font-weight: normal;
}

a.ext:link, a.ext:visited {
    text-decoration: none;
    color: red;
    font-weight: bold;
}


.tooltip {
  position: relative;
  opacity: 1;
  display: inline-block;
  border-bottom: 1px dotted black;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 180px;
  background-color: #6E6E6E;
  box-shadow: 4px 4px 6px #3b3b3b;
  color: #FFFFFF;
  text-align: center;
  border-radius: 6px;
  padding: 8px 0;
  left: 100%;
  opacity: 1;
  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip:hover .tooltiptext {
  right: 100%;
  opacity: 1;
  visibility: visible;
}


.iframe-embed {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: calc(100% + 26px);
    height: calc(100% + 26px);
    border: 0;
}

.iframe-embed-wrapper {
    position: relative;
    display: block;
    height: 0;
    padding: 0;
    overflow: hidden;
}
.iframe-embed-responsive-16by9 {
    padding-bottom: 66.25%;
}

ul {
    padding: 5px;
    margin: 10px 0;
    list-style: none;
    float: left;
}

ul li {
    float: left;
    display: inline; /*For ignore double margin in IE6*/
    margin: 0 10px;
}

ul li a {
    text-decoration: none;
    float:left;
    color: #999;
    cursor: pointer;
    font: 900 14px/22px "Arial", Helvetica, sans-serif;
}

ul li a span {
    margin: 0 10px 0 -10px;
    padding: 1px 8px 5px 18px;
    position: relative; /*To fix IE6 problem (not displaying)*/
    float:left;
}

ul.mmenu li a.current, ul.mmenu li a:hover {
    background: url(/images/buttonbg.png) no-repeat top right;
    color: #0d5f83;
}

ul.mmenu li a.current span, ul.mmenu li a:hover span {
    background: url(/images/buttonbg.png) no-repeat top left;
}

h1 {
    text-shadow: 2px 2px #<?php echo $bannerDropShaddows; ?>;
    text-align: center;
}

/* CSS Toggle Code here */
.toggle {
    position: absolute;
    margin-left: -9999px;
    visibility: hidden;
}

.toggle + label {
    display: block;
    position: relative;
    cursor: pointer;
    outline: none;
}

input.toggle-round-flat + label {
    padding: 1px;
    width: 33px;
    height: 18px;
    background-color: #dddddd;
    border-radius: 10px;
    transition: background 0.4s;
}

input.toggle-round-flat + label:before,
input.toggle-round-flat + label:after {
    display: block;
    position: absolute;
    content: "";
}

input.toggle-round-flat + label:before {
    top: 1px;
    left: 1px;
    bottom: 1px;
    right: 1px;
    background-color: #fff;
    border-radius: 10px;
    transition: background 0.4s;
}

input.toggle-round-flat + label:after {
    top: 2px;
    left: 2px;
    bottom: 2px;
    width: 16px;
    background-color: #dddddd;
    border-radius: 12px;
    transition: margin 0.4s, background 0.4s;
}

input.toggle-round-flat:checked + label {
    background-color: #<?php echo $backgroundBanners; ?>;
}

input.toggle-round-flat:checked + label:after {
    margin-left: 14px;
    background-color: #<?php echo $backgroundBanners; ?>;
}
.button {
  background-color: #356244;
  border: none;
  color: white;
  padding: 8px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
  font-weight: 500;
  margin: 4px 2px;
  border-radius: 8px;
  box-shadow: 0px 8px 10px rgba(0,0,0,0.1);
}
.link {background-color: #2A6594; outline:none;}
.link:hover {background-color: #3a87cd; outline:none;}
.blink {background-color: #b00; outline:none; color:white}
.blink:hover {background-color: #ff5722; outline:none;color:white}

.dropbtn {
  background-color: #2A659A;
  border: none;
  color: white;
  padding: 8px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
  font-weight: 500;
  margin: 4px 2px;
  border-radius: 8px;
  box-shadow: 0px 8px 10px rgba(0,0,0,0.1);
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 135px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: black;
  padding: 6px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #ddd;}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {display: block;}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {background-color: #3a87cd;}

//input[type=button],input[type=submit], input[type=reset] {
//  background-color: #448f47;
//  border: none;
//  color: white;
//  font-weight: 600;
//  font-size: 13px;
//  padding: 4px 12px;
//  text-decoration: none;
//  margin: 4px 4px;
//  cursor: pointer;
//  border-radius: 4px;
//}
input[type="radio"] {
  margin-top: -2px;
  vertical-align: middle;
}
input[type=text] {
  background-color: white;
  border: 1px solid #ccc;
  width:90px;
  color: #b5651d;
  font-size: 15px;
  font-weight: bold;
  padding: 4px 12px;
  text-decoration: none;
  margin: 4px 4px;
  border-radius: 4px;
  box-sizing: border-box;
}

.node {
    display: inline-block;
    width: 120px;
    padding: 2px;
    border: 1px solid #0f0;
    border-radius: 5px;
    margin: 3px 5px;
    text-align: center;
    font-weight:600;
    background: linear-gradient(to bottom, transparent 0%, #20cf52 0%), linear-gradient(#F7f7f7, #EEE);
}

#lact {
 height:25px;
 width: 100%;
 border-collapse: collapse;
 border:none;
}
#rcorner {
  display: flex;
  align-items: center;
  justify-content: center;
  vertical-align: middle;
  text-align:center;
  justify-content: center;
  align-items: center;
  border-radius: 10px;
  -moz-border-radius:10px;
  -webkit-border-radius:10px;
  border: 1px solid LightGrey;
  background: #e9e9e9;
  font: 10pt arial, sans-serif; 
  font-weight:bold;
  margin-top:2px;
  margin-right:0px;
  margin-left:0px;
  margin-bottom:0px;
  color:#002d62;
  white-space:normal;
  height: 100%;
  line-height:20px;
}
#rcornerh {
  justify-content: center;
  align-items: center;
  border:none;
  color:#002d62;
  font-size: 10pt;
  font-family: 'sans-serif', sans-serif;
  font-weight: bold;
  text-shadow: 1px 1px 1px White, 0 0 0.5em LightGrey, 0 0 1em Grey;
  height:20px;
  line-height:20px;
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
  background-image: linear-gradient(to bottom, #337ab7 0%, #265a88 100%);color:white;
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

div.parent{ 
    //border:solid black 1px;
    display:table;
    padding:5px; 
    width:100%;
    margin:0px 0; /* you can change/remove margin */
}
div.text{ 
    vertical-align:middle;
    display:table-cell;
    text-align:justify;
}
div.parent .img{
    vertical-align:middle;
    display:table-cell;
    padding-right:5px;
    width:70px; /* you can change width */
}
div.img img{ 
    width:100%;
    height:64px; /* you can change height */
    vertical-align:middle;
}
.hideScrollbar::-webkit-scrollbar{
  display: none; 
 }

