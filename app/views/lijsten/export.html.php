<?php header("Content-type: application/x-ms-download");
header("Content-Disposition: attachment; filename=".$filename);
header('Cache-Control: public'); 
echo $table;
?>
<style type="text/css">
	body {
				width: 670px;
				margin: 0 0 0 270px;
				font-family:Verdana, Geneva, Arial, Helvetica, sans-serif;
				position: relative;
				font-size: 12px;
			}
			h1 {
				font-family:Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size: large;
			}
			table {
				border: 1px solid #777;
				width: 70%;
				border-spacing: 0px;
				border-collapse:collapse
			}
			tr {
				
				margin: 0;
			}
			th {
				
			}
			td {
				border: 1px solid #777;
				padding: 3px 5px;
			}
			td.naam {
				min-width:220px;
			}
			tr:nth-child(even) {
				background-color: #ddd;
			}
			div#left {
				position: fixed;
				top: 50px;
				left: 0px;
				width: 220px;
				max-height: 600px;
				overflow:auto;
			}
			ul#werknemers{
				
				
			}
			#wn_toevoegen{
				margin-left: 40px;				
			}
			ul#werknemers li{
				border: 1px solid #777;
				border-radius: 5px;
				list-style: none;
				padding: 4px 4px;
				margin: 4px;
				cursor:move;
				font-size: 11px;
			}
			#personeel {
				border: 1px solid #777;
				border-radius: 5px;
				padding: 4px 4px;
				margin: 4px;
			}
			.dicht {
				float:right;		
				visibility: hidden;
				cursor: pointer;
			}
			.element:hover .dicht {
				visibility: visible;
			}
			li {
				display: block;
			}
			.element {
				position: relative;				
			}
			
			ul#buttons {
				float:right;
				margin: 0px 0px;
			}
			ul#buttons li {
				display: inline-block;
				width: 130px;
				border: 1px outset #666;
				background-color: #ddd;
				padding: 5px;
				border-radius: 4px;
				position: relative;
				margin-left: 6px;
				-webkit-box-align: center;
				text-align: center;
				cursor: default;
				box-shadow: 0 1px 0 #F8F8F8;
				cursor: pointer;
			}
			ul#buttons li:focus, ul#buttons li:hover{
				background-position: 0 -6px;	
				background: #bbb;
				border-color: #999;
			}
			ul#buttons li ul {
				position: absolute;
				visibility: hidden;
				left: 0px;
				top: 27px;
				border: 1px solid #aaa;
				border-radius: 5px;
				margin: 0 0;
				padding: 2px 0;
				background: #ddd;
				z-index: 100;
			}
			ul#buttons li ul li {
				border: 0px;
				padding: 2px 0px;
				margin-left: 0px;
				background: none;
				border-radius: 0px;
				box-shadow: 0 0;
				cursor: pointer;
				
			}
			
			textarea {
				font-family:Verdana, Geneva, Arial, Helvetica, sans-serif;
				position: relative;
				font-size: 10px;
				border-collapse: 1px;
			}
			
			
			
</style>