<!DOCTYPE html>
<html>
<!-- 
	WebPlotDigitizer - http://arohatgi.info/WebPlotDigitizer

	Copyright 2010-2016 Ankit Rohatgi <ankitrohatgi@hotmail.com>

	This file is part of WebPlotDigitizer.

    WebPlotDigitizer is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WebPlotDigitizer is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WebPlotDigitizer.  If not, see <http://www.gnu.org/licenses/>.
-->

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<meta name="Description" content="WebPlotDigitizer v3.10 - Web based tool to extract numerical data from plots and graph images."/>
<meta name="Keywords" content="Plot, Digitizer, WebPlotDigitizer, Ankit Rohatgi, Extract Data, Convert Plots, XY, Polar, Ternary, Map, HTML5"/>
<meta name="Author" content="Ankit Rohatgi"/>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
<meta http-equiv="Pragma" content="no-cache"/>
<meta http-equiv="Expires" content="0"/>
<title>WebPlotDigitizer - Copyright 2010-2016 Ankit Rohatgi</title>
<link rel="stylesheet" href="styles.css" type="text/css" media="screen" />
<link rel="stylesheet" href="widgets.css" type="text/css" media="screen" />



<!-- Handle Remote Data -->
<?php
// Server Settings:
$image_cache_folder = './image-cache';
$image_prefix = 'img-';

// Copy remote image to local directory and provide local and remote url to WPD
$remote_url = $_POST["imageURL"];
$file_extension = image_type_to_extension(exif_imagetype($remote_url), FALSE);
$temp_file_placeholder = tempnam($image_cache_folder, $image_prefix);
$local_image_filename = $temp_file_placeholder.".".$file_extension;
$local_image_relative_path = $image_cache_folder.'/'.pathinfo($local_image_filename, PATHINFO_FILENAME).'.'.$file_extension;

$copy_status = 'fail';
if(copy($remote_url, $local_image_filename)) {
    $file_size = filesize($local_image_filename);
    if($file_size < 50e6) { // Limit file size to ~50MB
        $copy_status = 'success';
        echo("<script>var wpdremote = { status: '{$copy_status}', remoteUrl: '{$remote_url}', localUrl: '{$local_image_relative_path}'};</script>");
    } else {
        $copy_status = 'fail';
    }
} else {
    $copy_status = 'fail';
}
if($copy_status == 'fail') {
    echo("<script>var wpdremote = { status: 'fail' };</script>");
}
unlink($temp_file_placeholder);
?>
<!-- End Remote Data Handling -->

<script src="combined-compiled.js"></script>

<!-- Start of StatCounter code -->
<script type="text/javascript">
var sc_project=9769742; 
var sc_invisible=1; 
var sc_security="3f89a4fe"; 
var scJsHost = (("https:" == document.location.protocol) ?
"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" +
scJsHost+
"statcounter.com/counter/counter.js'></"+"script>");
</script>
<noscript><div class="statcounter"><a title="free web stats"
href="http://statcounter.com/" target="_blank"><img
class="statcounter"
src="http://c.statcounter.com/9769742/0/3f89a4fe/1/"
alt="free web stats"></a></div></noscript>
<!-- End of StatCounder Code -->


</head>

<body>

<div id="loadingCurtain" style="position: absolute; top: 0px; left: 0px; z-index: 100; width: 100%; height: 100%; background-color: white;">
Loading application, please wait...
<br/>
<br/>
Problems loading? Make sure you have a recent version of Google Chrome, Firefox, Safari or Internet Explorer 11 installed.
</div>

<div id="allContainer">
    <!-- toolbar + graphics -->
    <div id="mainContainer">
        <div id="topContainer">
            <div id="menuButtonsContainer"><div class="wpd-menu">
    <div class="wpd-menu-header">File</div>
    <div class="wpd-menu-dropdown">
        <ul>
            <li id="wpd-filemenu-loadimage" onclick="wpd.popup.show('loadNewImage');">Load Image</li>
            <li id="wpd-filemenu-capture" onclick="wpd.webcamCapture.start();">Webcam Capture</li>
            <li id="wpd-filemenu-runscript" onclick="wpd.scriptInjector.start();">Run Script</li>
            <li id="wpd-filemenu-saveimage" onclick="wpd.graphicsWidget.saveImage();">Save Image</li>
            <li id="wpd-filemenu-exportdata" onclick="wpd.saveResume.save();">Export JSON</li>
            <li id="wpd-filemenu-import" onclick="wpd.saveResume.load();">Import JSON</li>
        </ul>
    </div>
</div>
<!--
<div class="wpd-menu">
    <div class="wpd-menu-header">Image</div>
    <div class="wpd-menu-dropdown">
        <ul>
            <li>Restore Image</li>
            <li>Crop</li>
            <li>Rotate</li>
            <li>Resize</li>
            <li>Grayscale</li>
            <li>Threshold</li>
        </ul>
    </div>
</div>
-->
<div class="wpd-menu">
    <div class="wpd-menu-header">Axes</div>
    <div class="wpd-menu-dropdown">
        <ul>
            <li id="wpd-axesmenu-defineaxes" onclick="wpd.popup.show('axesList');">Calibrate Axes</li>
            <li id="wpd-axesmenu-grid" onclick="wpd.gridDetection.start();">Remove Grid</li>
            <li id="wpd-axesmenu-perspective" onclick="wpd.perspective.start();">Perspective Transformation</li>
            <li id="wpd-axesmenu-tranformation-equations" onclick="wpd.transformationEquations.show();">Transformation Equations</li>
        </ul>
    </div>
</div>
<div class="wpd-menu">
    <div class="wpd-menu-header">Data</div>
    <div class="wpd-menu-dropdown">
        <ul>
            <li id="wpd-datamenu-acquire" onclick="wpd.acquireData.load();">Acquire Data</li>
            <li id="wpd-datamenu-manage" onclick="wpd.dataSeriesManagement.manage();">Manage Datasets</li>
        </ul>
    </div>
</div>
<div class="wpd-menu">
    <div class="wpd-menu-header">Measure</div>
    <div class="wpd-menu-dropdown">
        <ul>
            <li id="wpd-analyzemenu-distance" onclick="wpd.measurement.start(wpd.measurementModes.distance);">Distances</li>
            <li id="wpd-analyzemenu-angles" onclick="wpd.measurement.start(wpd.measurementModes.angle);">Angles</li>
            <!-- <li id="wpd-analyzemenu-open-path" onclick="wpd.measurement.start(wpd.measurementModes.openPath);">Path Length</li> -->
            <!-- <li id="wpd-analyzemenu-closed-path" onclick="wpd.measurement.start(wpd.measurementModes.closedPath);">Area &amp; Circumference</li> -->
        </ul>
    </div>
</div>
<div class="wpd-menu">
    <div class="wpd-menu-header">Help</div>
    <div class="wpd-menu-dropdown">
        <ul>
            <li id="wpd-helpmenu-about" onclick="wpd.popup.show('helpWindow');">About WebPlotDigitizer</li>
            <li id="wpd-helpmenu-tutorial"><a href="http://arohatgi.info/WebPlotDigitizer/tutorial.html" target="_blank">Tutorials</a></li>
            <li id="wpd-helpmenu-manual"><a href="http://arohatgi.info/WebPlotDigitizer/userManual.pdf" target="_blank">User Manual</a></li>
            <li id="wpd-helpmenu-github"><a href="https://github.com/ankitrohatgi/WebPlotDigitizer" target="_blank">GitHub Page</a></li>
            <li id="wpd-helpmenu-issues"><a href="https://github.com/ankitrohatgi/WebPlotDigitizer/issues" target="_blank">Report Issues</a></li>
            <li id="wpd-helpmenu-donate"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=CVFJGV5SNEV9J&lc=US&item_name=WebPlotDigitizer&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank">Donate (PayPal)</a></li>
        </ul>
    </div>
</div>
</div>

           
            <div id="topToolbarContainer">
                <!-- controls that show on top -->
                <div style="position:relative;"> 
                    <!-- Extra toolbars go here -->
                    <!-- Erase Toolbar -->
<div id="eraseToolbar" class="toolbar" style="width:350px;">
<p><input type="button" id="clearMaskBtn" value="Erase All" style="width:80px;" onclick="wpd.dataMask.clearMask();"/>
Stroke Width <input type="range" id="eraseThickness" min="1" max="150" value="20" style="width:100px;"></p>
</div>

<!-- Paint Toolbar -->
<div id="paintToolbar" class="toolbar" style="width:350px;">
<p>Stroke Width <input type="range" id="paintThickness" min="1" max="150" value="20" style="width:100px;"></p>
</div>

<!-- Adjust Points Toolbar -->
<div id="adjustDataPointsToolbar" class="toolbar" style="width:350px;">
<p><input type="button" value="View Keyboard Shortcuts" onclick="wpd.popup.show('adjust-data-points-keyboard-shortcuts-window');"/></p>
</div> 
                </div>
            </div>

             <div style="display:inline-block; position: absolute; top: 5px; right: 290px;" >
                <button type="button" title="Zoom in" onclick="wpd.graphicsWidget.zoomIn();" style="border:none; width:20px;">+</button>
                <button type="button" title="Zoom out" onclick="wpd.graphicsWidget.zoomOut();" style="border:none; width:20px;">-</button>
                <button type="button" title="View actual size" onclick="wpd.graphicsWidget.zoom100perc();" style="border:none;">100%</button>
                <button type="button" title="Fit to graphics area" onclick="wpd.graphicsWidget.zoomFit();" style="border:none;">Fit</button>
                <button title="Toggle extended crosshair" onclick="wpd.graphicsWidget.toggleExtendedCrosshairBtn();" style="border:none; width:20px; background-image: url('images/crosshair.png'); background-repeat: no-repeat; background-position: center;" id="extended-crosshair-btn">&nbsp;</button>
            </div>

        </div>

        <div id="graphicsContainer">
            <!-- the main canvas goes here -->
            <div id="canvasDiv" style="position:relative;">
                <canvas id="mainCanvas" class="canvasLayers" style="z-index:1;"></canvas>
                <canvas id="dataCanvas" class="canvasLayers" style="z-index:2;"></canvas>
                <canvas id="drawCanvas" class="canvasLayers" style="z-index:3;"></canvas>
                <canvas id="hoverCanvas" class="canvasLayers" style="z-index:4;"></canvas>
                <canvas id="topCanvas" class="canvasLayers" style="z-index:5;"></canvas>
            </div>
        </div>
    </div>

    <!-- sidebar + zoom -->
    <div id="sidebarContainer">
        <!-- zoom window goes here -->
        <div style="position:relative;" id="zoomDiv">
            <canvas id="zoomCanvas" class="zoomLayers" width=250 height=250 style="position:relative; top: 0px; left: 0px; z-index:1;"></canvas>
            <canvas id="zoomCrossHair" class="zoomLayers" width=250 height=250 style="position:absolute; top: 0px; left: 0px; z-index:2; background:transparent;"></canvas>
            <div id="cursorPosition" style="position:relative;">
            [<span id="mousePosition"></span>]
            </div>
        </div>

        <div id="zoom-settings-container"><input type="button" id="zoom-settings-button" title="Change zoom settings" value="⚙" onclick="wpd.zoomView.showSettingsWindow();"/></div>
        
        <div style="position:relative;" id="sidebarControlsContainer">
            <!-- side bars go here -->
            <!-- axes calibration -->
<div id="axes-calibration-sidebar" class="sidebar">
<p class="sidebar-title">Axes Calibration</p>
<p>Click points to select and use cursor keys to adjust positions. Use Shift+Arrow for faster movement. Click complete when finished.</p>
<p align="center"><input type="button" value="Complete!" style="width: 120px;" onclick="wpd.alignAxes.getCornerValues();"/></p>
</div>

<!-- manual mode -->
<div id="acquireDataSidebar" class="sidebar">
<p class="sidebar-title">Manual Mode <input type="button" value="Automatic Mode" style="width: 125px;" onclick="wpd.autoExtraction.start();"></p>
<hr/>
<p>Dataset <select id="manual-sidebar-dataset-list" onchange="wpd.acquireData.changeDataset(this);" style="width:160px;"></select></p>
<hr/>
<p>
    <input type="button" value="Add Point (A)" onclick="wpd.acquireData.manualSelection();" style="width:115px;" id="manual-select-button">
    <input type="button" value="Adjust Point (S)" onClick="wpd.acquireData.adjustPoints();" style="width: 115px;" id="manual-adjust-button">
</p>
<div class="vertical-spacer"></div>
<p>
    <input type="button" value="Delete Point (D)" onclick="wpd.acquireData.deletePoint();" style="width: 115px;" id="delete-point-button">
    <input id="clearAllBtn" type="button" value="Clear Points" onCLick="wpd.acquireData.clearAll();" style="width: 115px;">
</p>
<div class="vertical-spacer"></div>
<p>
    <input type="button" value="Edit Labels (E)" id="edit-data-labels" onclick="wpd.acquireData.editLabels();" style="display: none; width: 115px;">
    <input type="button" value="View Data" id="saveBtn" onclick="wpd.dataTable.showTable();" style="width:115px;">
</p>
<div class="vertical-spacer"></div>
<p>Data Points: <span class="data-point-counter">0</span></p>
</div>

<!-- edit image -->
<div id="editImageToolbar" class="sidebar">
<p align="center"><b>Edit Image</b></p>
<p align="center"><input type="button" value="H. Flip" style="width: 75px;" onclick="hflip();"><input type="button" value="V. Flip" style="width: 75px;" onClick="vflip();"></p>
<p align="center"><input type="button" value="Crop" style="width: 150px;" onclick="cropPlot();"></p>
<p align="center"><input type="button" value="Restore" style="width: 150px;" onclick="restoreOriginalImage();"></p>
<p align="center"><input type="button" value="Save .PNG" style="width: 150px;" onclick="savePNG();"></p>
</div>

<!-- automatic mode -->
<div id="auto-extraction-sidebar" class="sidebar">
<p class="sidebar-title">Automatic Mode <input type="button" value="Manual Mode" style="width:110px;" onclick="wpd.acquireData.load();"/></p>
<hr/>
<p>Dataset <select id="automatic-sidebar-dataset-list" onchange="wpd.acquireData.changeDataset(this);" style="width:160px;"></select></p>
<hr/>
<p>Mask <input type="button" value="Box" style="width:50px;" onclick="wpd.dataMask.markBox();" id="box-mask"><input type="button" value="Pen" style="width:45px;" onClick="wpd.dataMask.markPen();" id="pen-mask"><input type="button" value="Erase" style="width:50px;" onClick="wpd.dataMask.eraseMarks();" id="erase-mask"><input type="button" value="View" style="width:40px;" onclick="wpd.dataMask.viewMask();" id="view-mask"/></p>
<hr/>
<p>Color <select id="color-detection-mode-select" onchange="wpd.colorPicker.changeDetectionMode();"><option value="fg">Foreground Color</option><option value="bg">Background Color</option></select><input type="button" id="color-button" value=" " onclick="wpd.colorPicker.startPicker();" style="width: 25px;" title="Click to change color"/></p>
<p>Distance <td><td><input type="text" size="3" id="color-distance-value" onchange="wpd.colorPicker.changeColorDistance();"/>
<input type="button" value="Filter Colors" onclick="wpd.colorPicker.testColorDetection();" style="width: 90px;"></p>
<hr/>
<p>Algorithm
<select id="auto-extract-algo-name" onchange="wpd.algoManager.applyAlgoSelection();"></select>
</p>
<div id="algo-parameter-container" style="margin-left: 10px; margin-top: 5px;"></div>
<div class="vertical-spacer"></div>
<p style="margin-top: 5px;">
    <input type="button" value="Run" style="width:40px;" onclick="wpd.algoManager.run();"/>
    <input type="button" value="Clear Points" style="width:95px;" onclick="wpd.acquireData.clearAll();"/>
    <input type="button" value="View Data" style="width:80px;" onclick="wpd.dataTable.showTable();"/>
</p>
<hr/>
<p>Data Points: <span class="data-point-counter">0</span></p>
</div>

<!-- distance measurement -->
<div id="measure-distances-sidebar" class="sidebar">
<p class="sidebar-title">Measure Distances</p>
<p>
    <input type="button" value="Add Pair (A)" style="width: 115px;" id="add-pair-button" onclick="wpd.measurement.addItem();"/>
    <input type="button" value="Delete Pair (D)" style="width: 115px;" id="delete-pair-button" onclick="wpd.measurement.deleteItem();"/> 
</p>
<div class="vertical-spacer"></div>
<p>
    <input type="button" value="Clear All" style="width: 115px;" id="clear-all-pairs-button" onclick="wpd.measurement.clearAll();"/>
    <input type="button" value="View Data" style="width: 115px;" id="view-measurement-data-button" onclick="wpd.dataTable.showDistanceData();"/>
</p>
</div>

<!-- angle measurement -->
<div id="measure-angles-sidebar" class="sidebar">
<p class="sidebar-title">Measure Angles</p>
<p>
    <input type="button" value="Add Angle (A)" style="width: 115px;" id="add-angle-button" onclick="wpd.measurement.addItem();"/>
    <input type="button" value="Delete Angle (D)" style="width: 115px;" id="delete-angle-button" onclick="wpd.measurement.deleteItem();"/>
</p>
<div class="vertical-spacer"></div>
<p>
    <input type="button" value="Clear All" style="width: 115px;" onclick="wpd.measurement.clearAll();"/>
    <input type="button" value="View Data" style="width: 115px;" onclick="wpd.dataTable.showAngleData();"/>
</p>
</div>

<!-- open path measurement -->
<div id="measure-open-path-sidebar" class="sidebar">
<p class="sidebar-title">Measure Path</p>
<p>
    <input type="button" value="Add Path (A)" style="width: 115px;" id="add-open-path-button" onclick="wpd.measurement.addItem();"/>
    <input type="button" value="Delete Path (D)" style="width: 115px;" id="delete-open-path-button" onclick="wpd.measurement.deleteItem();"/>
</p>
<div class="vertical-spacer"></div>
<p>
    <input type="button" value="Clear All" style="width: 115px;" onclick="wpd.measurement.clearAll();"/>
    <input type="button" value="View Data" style="width: 115px;" onclick="wpd.dataTable.showOpenPathData();"/>
</p>
</div>

<!-- closed path measurement -->
<div id="measure-closed-path-sidebar" class="sidebar">
<p class="sidebar-title">Measure Closed Path</p>
<p>
    <input type="button" value="Add Path (A)" style="width: 115px;" id="add-closed-path-button" onclick="wpd.measurement.addItem();"/>
    <input type="button" value="Delete Path (D)" style="width: 115px;" id="delete-closed-path-button" onclick="wpd.measurement.deleteItem();"/>
</p>
<div class="vertical-spacer"></div>
<p>
    <input type="button" value="Clear All" style="width: 115px;" onclick="wpd.measurement.clearAll();"/>
    <input type="button" value="View Data" style="width: 115px;" onclick="wpd.dataTable.showClosedPathData();"/>
</p>
</div>

<!-- grid detection -->
<div id="grid-detection-sidebar" class="sidebar">
<p class="sidebar-title">Detect Grid</p>
<p>
    Mask
    <input type="button" value="Box" style="width: 60px;" id="grid-mask-box" onclick="wpd.gridDetection.markBox();"/>
    <input type="button" value="Clear" style="width: 60px;" id="grid-mask-clear" onclick="wpd.gridDetection.clearMask();"/>
    <input type="button" value="View"  style="width: 60px;" id="grid-mask-view" onclick="wpd.gridDetection.viewMask();"/>
</p>
<hr/>
<p>
    Color
    <input type="button" value="Pick" style="width: 60px;" id="grid-color-picker-button" onclick="wpd.gridDetection.startColorPicker();"/>
    <input type="text" value="10" style="width: 60px;" id="grid-color-distance" onchange="wpd.gridDetection.changeColorDistance();"/>
    <input type="button" value="Test" style="width: 60px;" id="grid-color-test" onclick="wpd.gridDetection.testColor();"/>
</p>
<p align="center"><input type="checkbox" id="grid-background-mode" checked onchange="wpd.gridDetection.changeBackgroundMode();"/> Background Mode</p>
<hr/>
<table>
    <tr><td align="right">Horizontal </td><td><input type="checkbox" id="grid-horiz-enable" checked/></td></tr>
    <tr><td align="right">X% </td><td>&nbsp; <input type="text" value="80" id="grid-horiz-perc" style="width: 40px;"/></td></tr>
    <tr><td align="right">Vertical </td><td><input type="checkbox" id="grid-vert-enable" checked/></td></tr>
    <tr><td align="right">Y% </td><td>&nbsp; <input type="text" value="80" id="grid-vert-perc" style="width: 40px;"/></td></tr>
</table>
<hr/>
<p align="center">
    <input type="button" value="Detect" style="width: 100px;" onclick="wpd.gridDetection.run();"/>
    &nbsp;
    <input type="button" value="Reset" style="width: 100px;" onclick="wpd.gridDetection.reset();"/>
</p>
</div>
        </div>

    </div>
</div>

<!-- popup windows go here -->
    <!-- Background curtain for popups -->
	<div id="shadow" style="width:100%; height:100%; background-color: rgba(0,0,0,0.3); position:absolute; top:0px; left:0px; z-index:50; visibility:hidden;">
	</div>

    <!-- Load Image -->
	<div id="loadNewImage" class="popup" style="width: 400px;">
	<div class="popupheading">Load Image File</div>
	<p>&nbsp;</p>
	<p align="center"><input type="file" id="fileLoadBox"/></p>
	<p>&nbsp;</p>
	<p align="center">
        <input type="button" value="Load" onclick="wpd.graphicsWidget.load();"/>
        <input type="button" value="Cancel" onclick="wpd.popup.close('loadNewImage');"/>
    </p>
	</div>

    <!-- Zoom Settings -->
    <div id="zoom-settings-popup" class="popup" style="width: 300px;">
    <div class="popupheading">Magnified View Settings</div>
    <p>&nbsp;</p>
    <center>
    <table>
    <tr><td><p>Magnification: </p></td><td><p><input type="text" id="zoom-magnification-value" size="3"/> Times</p></td></tr>
    <tr>
        <td><p>Crosshair Color: </p></td>
        <td><p>
        <select id="zoom-crosshair-color-value">
            <option value="black">Black</option>
            <option value="red">Red</option>
            <option value="yellow">Yellow</option>
        </select>
        </td>
    </tr>
    </table>
    </center>
    <p>&nbsp;</p>
    <p align="center"><input type="button" value="Apply" onclick="wpd.zoomView.applySettings();"/> <input type="button" value="Cancel" onclick="wpd.popup.close('zoom-settings-popup');"/></p>
    </div>
 
    <!-- Run Script -->
    <div id="runScriptPopup" class="popup" style="width: 500px;">
    <div class="popupheading">Run Script</div>
    <p>&nbsp;</p>
    <p align="center">Load a Javascript file to further extend the capabilities of WebPlotDigitizer. For examples, visit the <a href="http://github.com/ankitrohatgi/WebPlotDigitizer-Examples" target="_blank">WebPlotDigitizer-Examples repository</a>.</p>
    <p>&nbsp;</p>
    <p align="center"><input type="file" id="runScriptFileInput"/></p>
    <p>&nbsp;</p>
    <p align="center"><input type="button" value="Run" onclick="wpd.scriptInjector.load();"/> <input type="button" value="Cancel" onclick="wpd.scriptInjector.cancel();"/></p>
    </div>

    <!-- Webcam Capture -->
    <div id="webcamCapture" class="popup" style="width: 650px;">
    <div class="popupheading">Webcam Capture</div>
    <p>&nbsp;</p>
    <p align="center"><video id="webcamVideo" autoplay="true" height="350"></video></p>
    <p align="center"><input type="button" value="Capture" onclick="wpd.webcamCapture.capture();"/> <input type="button" value="Cancel" onclick="wpd.webcamCapture.cancel();"/></p>
    </div>

    <!-- Generic Message Popup -->
    <div id="messagePopup" class="popup" style="width: 400px;">
    <div id="message-popup-heading" class="popupheading"></div>
    <p>&nbsp;</p>
    <p align="center" id="message-popup-text"></p>
    <p>&nbsp;</p>
    <p align="center"><input type="button" value="OK" onclick="wpd.messagePopup.close()"/></p>
    </div>

    <!-- Generic Ok/Cancel Popup -->
    <div id="okCancelPopup" class="popup" style="width: 400px;">
    <div id="ok-cancel-popup-heading" class="popupheading"></div>
    <p>&nbsp;</p>
    <p align="center" id="ok-cancel-popup-text"></p>
    <p>&nbsp;</p>
    <p align="center"><input type="button" value="OK" onclick="wpd.okCancelPopup.ok()"/> <input type="button" value="Cancel" onclick="wpd.okCancelPopup.cancel()"/></p>
    </div>

    <!-- Choose axes type -->
	<div id="axesList" class="popup" style="width: 400px;">
	<div class="popupheading">Choose Plot Type</div>
	<p>&nbsp;</p>
	<center>
	<table>
	<tr><td align="left"><label><input type="radio" name="plotlisting" id="r_xy" checked> 2D (X-Y) Plot</label></td></tr>
	<tr><td align="left"><label><input type="radio" name="plotlisting" id="r_bar"> 2D Bar Plot</label></td></tr>
	<tr><td align="left"><label><input type="radio" name="plotlisting" id="r_polar"> Polar Diagram</label></td></tr>
	<tr><td align="left"><label><input type="radio" name="plotlisting" id="r_ternary"> Ternary Diagram</label></td></tr>
	<tr><td align="left"><label><input type="radio" name="plotlisting" id="r_map"> Map With Scale Bar</label></td></tr>
	<tr><td align="left"><label><input type="radio" name="plotlisting" id="r_image"> Image</label></td></tr>
	</table>
	</center>
	<p>&nbsp;</p>
	<p align="center"><input type="button" value="Align Axes" onclick="wpd.alignAxes.start();">&nbsp;<input type="button" value="Cancel" onClick="wpd.popup.close('axesList');"></p>
	</div>

    <!-- XY Alignment -->
	<div id="xyAlignment" class="popup" style="width: 400px;">
	<div class="popupheading">X and Y Axes Calibration</div>
	<p>&nbsp;</p>
	<p align="center">Enter X-values of the two points clicked on X-axis and Y-values of the two points clicked on Y-axes</p>
	<center>
	<table padding="10">
		<tr>
            <td></td>
			<td align="center" valign="bottom">Point 1</td>
			<td align="center" valign="bottom" width="80">Point 2</td>
			<td align="center" valign="bottom" width="82">Log Scale</td>
		</tr>
	    <tr>
            <td align="center">X-Axis:</td>
	        <td align="center"><input type="text" size="8" id="xmin" value="0" /></td>
	        <td align="center"><input type="text" size="8" id="xmax" value="1" /></td>
	        <td align="center"><input type="checkbox" id="xlog"></td>
	    </tr>
	    <tr>
            <td align="center">Y-Axis:</td>
	        <td align="center"><input type="text" size="8" id="ymin" value="0" /></td>
	        <td align="center"><input type="text" size="8" id="ymax" value="1" /></td>
	        <td align="center"><input type="checkbox" id="ylog" /></td>
	    </tr>
	</table>
	<p align="center" class="footnote">*For dates, use yyyy/mm/dd format (e.g. 2013/10/23 or 2013/10). For exponents, enter values as 1e-3 for 10^-3.</p>
	<p>&nbsp;</p>
	<p align="center"><input type="button" id="xybtn" value="OK" onclick="wpd.alignAxes.align();" /></p>
	</center>
	</div>

    <!-- Bar Alignment -->
    <div id="barAlignment" class="popup" style="width: 400px;">
    <div class="popupheading">Bar Chart Calibration</div>
    <p align="center">Enter the values at the two points selected on the continuous axes along the bars</p>
    <center>
    <table padding="10">
        <tr>
            <td align="center" valign="bottom">Point 1</td>
            <td align="center" valign="bottom" width="80">Point 2</td>
            <td align="center" valign="Log Scale" width="80">Log Scale</td>
        </tr>
        <tr>
            <td align="center"><input type="text" size="8" id="bar-axes-p1" value="0" /></td>
            <td align="center"><input type="text" size="8" id="bar-axes-p2" value="1" /></td>
            <td align="center"><input type="checkbox" id="bar-axes-log-scale"/></td>
        </tr>
    </table>
    </center>
    <p>&nbsp;</p>
    <p align="center"><input type="button" value="OK" onclick="wpd.alignAxes.align();"/></p>
    </div>

    <!-- Map Alignment -->
	<div id="mapAlignment" class="popup" style="width: 200px;">
	<div class="popupheading">Scale Size</div>
	<p>&nbsp;</p>
	<p align="center"><input type="text" size="6" id="scaleLength" value="1"> <input type="text" size="6" id="scaleUnits" value="Units"/></p>
	<p>&nbsp;</p>
	<p align="center"><input type="button" id="xybtn" value="OK" onclick="wpd.alignAxes.align();"></p>
	</div>

    <!-- Polar Alignment -->
	<div id="polarAlignment" class="popup" style="width: 400px;">
    <div class="popupheading">Align Polar Axes</div>
    <center>
    <table padding="15">
        <tr>
            <td>&nbsp;</td>
            <td align="center"><b>Point 1</b></td>
            <td align="center"><b>Point 2</b></td>
            <td align="center"><b>Log Scale</b></td>
        </tr>
        <tr>
            <td>R: </td>
            <td align="center"><input type="text" size="6" id="polar-r1" value="1"/></td>
            <td align="center"><input type="text" size="6" id="polar-r2" value="1"/></td>
            <td align="center"><input type="checkbox" id="polar-log-scale"/></td>
        </tr>
        <tr>
            <td>Θ: </td>
            <td align="center"><input type="text" size="6" id="polar-theta1" value="1"/></td>
            <td align="center"><input type="text" size="6" id="polar-theta2" value="1"/></td>
            <td align="center">&nbsp;</td>
        </tr>
    </table>
    </center>
	<p align="center"><label><input type="radio" id="polar-degrees" name="angleUnits" checked> Degrees</label> <label><input type="radio" id="polar-radians" name="angleUnits"> Radians</p></label>
	<p align="center"><input type="checkbox" id="polar-clockwise"> Clockwise</p>
    <br/>
	<p align="center"><input type="button" value="OK" onclick="wpd.alignAxes.align();"></p>
	</div>

    <!-- Ternary Alignment -->
	<div id="ternaryAlignment" class="popup">
	<div class="popupheading">Select Range of Variables</div>
	<p>&nbsp;</p>
	<p align="center">Axes Orientation</p>
	<center>
	<table>
	  <tr><td><img src="images/ternarynormal.png" width="200"></td><td><img src="images/ternaryreverse.png" width="200"></td></tr>
	  <tr><td><p align="center"><input type="radio" name="ternaryOrientation" id="ternarynormal" checked> Normal</p></td><td><p align="center"><input type="radio" name="ternaryOrientation" id="ternaryreverse"> Reverse</p></td></tr>
	</table>
	</center>
	<p align="center">Range of Variables</p>
	<center>
	<table><tr><td><p align="center"><input type="radio" id="range0to1" name="ternaryRange" checked> 0 to 1&nbsp;&nbsp;</p></td><td><p align="center">&nbsp;&nbsp;<input type="radio" id="range0to100" name="ternaryRange"> 0 to 100</p></td></tr></table>
	</center>
	<p>&nbsp;</p>
	<p align="center"><input type="button" value="OK" onclick="wpd.alignAxes.align();"></p>
	</div>

    <!-- View Data -->
	<div id="csvWindow" class="popup" style="width: 800px; height: 480px;">
	<div class="popupheading">Acquired Data</div>
    <table style="width:780px;">
    <tr>
    <td>
    <!-- left panel -->
    <span id="data-table-dataset-control"><p>Dataset: <select id="data-table-dataset-list" onchange="wpd.dataTable.changeDataset();"></select></p></span>
    <p align="center">Variables: <span id="dataVariables"></span></p>
	<p align="center"><textarea id="digitizedDataTable" style="width: 480px; height: 250px;"></textarea></p>
	<p align="center">
		<input type="button" value="Select All" onclick="wpd.dataTable.selectAll();"/>
        <input type="button" value="Download .CSV" onclick="wpd.dataTable.generateCSV();"/>
		<input type="button" value="Graph in Plotly*" onclick="wpd.dataTable.exportToPlotly();"/>
		<input type="button" value="Close" onclick="wpd.popup.close('csvWindow');"/>
	</p>
	<p align="center" class="footnote">*Plotly is a secure data analysis and graphing site with data sharing and access controls.</p>
	<p align="center" class="footnote">Visit <a href="http://plot.ly" target="plotlyWebsite">http://plot.ly</a> for details.</p>
    </td>
    <td valign="top" style="width:250px;">
    <!-- data side controls -->
    <p><b>Sort</b></p>
    <p class="leftIndent">Sort by: <select id="data-sort-variables" onchange="wpd.dataTable.reSort();"></select></p>
    <p class="leftIndent">Order: 
		<select id="data-sort-order" onchange="wpd.dataTable.reSort();">
			<option value="ascending">Ascending</option>
			<option value="descending">Descending</option>
		</select>
	</p>
    <hr/>
    <p><b>Format</b></p>
	<p class="leftIndent">
		<span id="data-date-formatting-container">
		Date Formatting:
        <span id="data-date-formatting"></span>         
		</span>
	</p>
    <p class="leftIndent">Number Formatting:</p>
    <p>Digits: <input type="text" value="5" size="2" id="data-number-format-digits"/>
        <select id="data-number-format-style">
            <option value="ignore">Ignore</option>
            <option value="fixed">Fixed</option>
            <option value="precision">Precision</option>
            <option value="exponential">Exponential</option>
        </select>
    </p>
    <p>Column Separator: <input type="text" value=", " size="2" id="data-number-format-separator"/></p>
	<p align="right"><input type="button" value="Format" onclick="wpd.dataTable.reSort();"/></p>
    </td>
    </tr>
    </table>
	</div>

    <!-- XY Axes Calibration Instructions -->
	<div id="xyAxesInfo" class="popup" style="width:400px;"> 
	<div class="popupheading">Align X-Y Axes</div>
	<p>&nbsp;</p>
	<p align="center"><img src="images/xyaxes.png" /></p>
	<p align="center">Click four known points on the axes in the <font color="red">order shown in red</font>. Two on the X axis (X1, X2) and two on the Y axis (Y1, Y2).</p>
	<p>&nbsp;</p>
	<p align="center"><input type="button" value="Proceed" onclick="wpd.xyCalibration.pickCorners();" /></p>
	</div>

    <!-- Bar Chart Axes Calibration Intructions -->
    <div id="barAxesInfo" class="popup" style="width:650px;">
    <div class="popupheading">Align Bar Chart Axes</div>
    <p>&nbsp;</p>
    <p align="center"><img src="images/barchart.png" /></p>
    <p align="center">Click on two known points (P1, P2) on the continuous axes along the bars</p>
    <p>&nbsp;</p>
    <p align="center"><input type="button" value="Proceed" onclick="wpd.barCalibration.pickCorners();" /></p>
    </div>

    <!-- Map Axes Calibration Instructions -->
	<div id="mapAxesInfo" class="popup" style="width: 350px;"> 
	<div class="popupheading">Align Map To Scale Bar</div>
	<p>&nbsp;</p>
	<p align="center"><img src="images/map.png" /></p>
	<p align="center">Click on the two ends of the scale bar on the map.</p>
	<p>&nbsp;</p>
	<p align="center"><input type="button" value="Proceed" onclick="wpd.mapCalibration.pickCorners();"></p>
	</div>

    <!-- Polar Axes Calibration Instructions -->
	<div id="polarAxesInfo" class="popup" style="width: 350px;">
	<div class="popupheading">Align Polar Axes</div>
	<p>&nbsp;</p>
	<p align="center"><img src="images/polaraxes.png" /></p>
	<p align="center">Click on the center, followed by two known points.</p>
	<p>&nbsp;</p>
	<p align="center"><input type="button" value="Proceed" onclick="wpd.polarCalibration.pickCorners();"></p>
	</div>

    <!-- Ternary Axes Calibration Instructions -->
	<div id="ternaryAxesInfo" class="popup" style="width: 350px;">
	<div class="popupheading">Align Ternary Axes</div>
	<p>&nbsp;</p>
	<p align="center"><img src="images/ternaryaxes.png" /></p>
	<p align="center">Click on the three corners in the order shown above.</p>
	<p>&nbsp;</p>
	<p align="center"><input type="button" value="Proceed" onclick="wpd.ternaryCalibration.pickCorners();"></p>
	</div>

    <!-- About WPD -->
	<div id="helpWindow" class="popup" style="width: 600px;">
	<div class="popupheading">WebPlotDigitizer - Web Based Plot Digitizer</div>
	<p>&nbsp;</p>
    <p align="center">Version 3.10</p>
	<p align="center">This program is distributed under the <a href="https://www.gnu.org/licenses/gpl-3.0-standalone.html" target="_blank">GNU General Public License Version 3</a>.</p>
	<p align="center">Copyright 2010-2015 Ankit Rohatgi &lt;ankitrohatgi@hotmail.com&gt;</p>
	<p align="center"><a href="http://arohatgi.info/WebPlotDigitizer" target="website">http://arohatgi.info/WebPlotDigitizer</a></p>
	<p>&nbsp;</p>
	<p align="center"><input type="button" value="Close" onclick="wpd.popup.close('helpWindow');"></p>
	</div>
    
    <!-- Color Selection -->
    <div id="color-selection-widget" class="popup" style="width:400px;">
	<div id="color-selection-title" class="popupheading">Specify Color</div>
	<p align="center">&nbsp;</p>
    <div style="text-align:center;"><div id="color-selection-selected-color-box" class="largeColorBox"></div></div>
	<p align="center">&nbsp;</p>
	<p align="center">R:<input type="text" value="255" id="color-selection-red" size="3">&nbsp;
	G:<input type="text" id="color-selection-green" value="255" size="3">&nbsp; B:<input type="text" id="color-selection-blue" value="255" size="3">
	 <input type="button" value="Color Picker" onclick="wpd.colorSelectionWidget.pickColor();"></p>
	<p align="center">&nbsp;</p>
    <p>Dominant Colors: <div id="color-selection-options" style="text-align:center;"></div></p>
    <p>&nbsp;</p>
	<p align="center"><input type="button" value="Done" onclick="wpd.colorSelectionWidget.setColor();"></p>
	</div>


    <!-- Manage Data Series -->
    <div id="manage-data-series-window" class="popup" style="width:400px;">
    <div class="popupheading">Manage Datasets</div>
    <p>&nbsp;</p>
    <center>
    <table>
    <tr>
        <td align="right">Selected Dataset: </td><td> &nbsp;<select id="manage-data-series-list" style="width:200px;" onchange="wpd.dataSeriesManagement.changeSelectedSeries();"><option>Default Dataset</option></select></td>
    </tr>
    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
    <tr>
        <td align="right">Dataset Name: </td><td> &nbsp;
        <input type="text" id="manage-data-series-name" onblur="wpd.dataSeriesManagement.editSeriesName();" onchange="wpd.dataSeriesManagement.editSeriesName();"/>
        <input type="button" value="Change" onclick="wpd.dataSeriesManagement.editSeriesName();"/></td>
    </tr>
    <tr>
        <td align="right">Data Points: </td><td> &nbsp;<span id="manage-data-series-point-count">0</span></td>
    </tr>
    </table>
    </center>
    <p>&nbsp;</p>
    <p align="center">
        <input type="button" value="Add" onclick="wpd.dataSeriesManagement.addSeries();"/>
        <input type="button" value="Delete" onclick="wpd.dataSeriesManagement.deleteSeries();"/>
        <input type="button" value="View Data" onclick="wpd.dataSeriesManagement.viewData();"/>
        <input type="button" value="Close" onclick="wpd.popup.close('manage-data-series-window');"/>
    </p>
    </div>

    <!-- Axes Transformation Equations -->
    <div id="axes-transformation-equations-window" class="popup" style="width:600px;">
    <div class="popupheading">Transformation Equations</div>
    <p>The following relationships are being used to convert image pixels to data:</p>
    <div id="axes-transformation-equation-list"></div>
    <p>&nbsp;</p>
    <p align="center"><input type="button" value="Close" onclick="wpd.popup.close('axes-transformation-equations-window');"/></p>
    </div>

    <!-- Export JSON -->
    <div id="export-json-window" class="popup" style="width:500px;">
    <div class="popupheading">Export JSON</div>
    <p>This JSON file contains the axes calibration and the digitized data points from this plot. This file can be imported later to resume work or reuse the calibration in another plot.</p>
    <p>&nbsp;</p>
    <p align="center">
        <input type="button" value="Download" onclick="wpd.saveResume.download();"/>
        <input type="button" value="Close" onclick="wpd.popup.close('export-json-window');"/>
    </p>
    </div>

    <!-- Import JSON -->
    <div id="import-json-window" class="popup" style="width:500px;">
    <div class="popupheading">Import JSON</div>
    <p>Specify a previously exported JSON file to load here. Note that this will clear any unsaved data points in the current plot.</p>
    <p>&nbsp;</p>
    <p align="center">JSON File: <input type="file" id="import-json-file"/></p>
    <p>&nbsp;</p>
    <p align="center">
        <input type="button" value="Import" onclick="wpd.saveResume.read();"/>
        <input type="button" value="Cancel" onclick="wpd.popup.close('import-json-window');"/>
    </p>
    </div>

    <!-- Adjust Data Points Keyboard Shortcuts -->
    <div id="adjust-data-points-keyboard-shortcuts-window" class="popup" style="width:400px;">
    <div class="popupheading">Keyboard Shortcuts</div>
    <p>Click to select a data point. The following keys can then be used to the adjust the position:</p>
    <center>
    <table cellspacing="5" border="0">
    <tr><td align="right">Cursor (Arrows) -</td><td>Move up/down/right/left</td></tr>
    <tr><td align="right">Shift + Cursor -</td><td>Faster rate of movement</td></tr>
    <tr><td align="right">Q -</td><td>Select next point</td></tr>
    <tr><td align="right">W -</td><td>Select previous point</td></tr>
    <tr><td align="right">Del/Backspace -</td><td>Delete point</td></tr>
    <tr><td align="right">E -</td><td>Edit label (Bar Chart)</td></tr>
    </table>
    </center>
    <p>&nbsp;</p>
    <p align="center"><input type="button" value="Close" onclick="wpd.popup.close('adjust-data-points-keyboard-shortcuts-window');"/></p>
    </div>

    <!-- Data point label editor -->
    <div id="data-point-label-editor" class="popup" style="width:280px;">
    <div class="popupheading">Edit Label</div>
    <p>&nbsp;</p>
    <p align="center">Label: <input type="text" value="Data Point" id="data-point-label-field" onkeydown="wpd.dataPointLabelEditor.keydown(event);"/></p>
    <p>&nbsp;</p>
    <p align="center">
        <input type="button" value="OK" onclick="wpd.dataPointLabelEditor.ok();"/>
        <input type="button" value="Cancel" onclick="wpd.dataPointLabelEditor.cancel();"/>
    </p>
    </div>

    <!-- Perspective Transform Instructions -->
    <div id="perspective-info" class="popup" style="width:500px;">
    <div class="popupheading">Perspective Transformation</div>
    <p align="center"><img src="images/perspective.png" width="350"></p>
    <br/>
    <p align="center">Click on four corners of the region to be transformed as shown.</p>
    <br/>
    <p align="center">
        <input type="button" value="OK" onclick="wpd.perspective.pickCorners();"/>
        <input type="button" value="Cancel" onclick="wpd.popup.close('perspective-info');"/>
    </p>
    </div>

<!-- strings for translation -->
<div class="i18n-string" id="i18n-string-wpd">WebPlotDigitizer</div>
<div class="i18n-string" id="i18n-string-unstable-version-warning">Unstable version warning!</div>
<div class="i18n-string" id="i18n-string-unstable-version-warning-text">You are using a beta version of WebPlotDigitizer. There may be some issues with the software that are expected.</div>
<div class="i18n-string" id="i18n-string-import-json">Import JSON</div>
<div class="i18n-string" id="i18n-string-json-data-loaded">JSON data has been loaded!</div>
<div class="i18n-string" id="i18n-string-calibration-invalid-inputs">Invalid Inputs</div>
<div class="i18n-string" id="i18n-string-calibration-enter-valid">Please enter valid values for calibration.</div>
<div class="i18n-string" id="i18n-string-acquire-data">Acquire Data</div>
<div class="i18n-string" id="i18n-string-acquire-data-calibration">Please calibrate the axes before acquiring data.</div>
<div class="i18n-string" id="i18n-string-clear-data-points">Clear data points?</div>
<div class="i18n-string" id="i18n-string-clear-data-points-text">This will delete all data points from this dataset</div>
<div class="i18n-string" id="i18n-string-webcam-capture">Webcam Capture</div>
<div class="i18n-string" id="i18n-string-webcam-capture-text">Your browser does not support webcam capture using HTML5 APIs. A recent version of Google Chrome is recommended.</div>
<div class="i18n-string" id="i18n-string-transformation-eqns">Transformation Equations</div>
<div class="i18n-string" id="i18n-string-transformation-eqns-text">Transformation equations are available only after axes have been calibrated.</div>
<div class="i18n-string" id="i18n-string-unsupported">Unsupported Feature!</div>
<div class="i18n-string" id="i18n-string-unsupported-text">This feature has not been implemented in the current version. This may be available in a future release.</div>
<div class="i18n-string" id="i18n-string-processing">Processing</div>
<div class="i18n-string" id="i18n-string-invalid-file">ERROR: Invalid File!</div>
<div class="i18n-string" id="i18n-string-invalid-file-text">Please load a valid image file. Common image formats such as JPG, PNG, BMP, GIF etc. should work. PDF or Word documents are not accepted.</div>
<div class="i18n-string" id="i18n-string-raw">Raw</div>
<div class="i18n-string" id="i18n-string-nearest-neighbor">Nearest Neighbor</div>
<div class="i18n-string" id="i18n-string-manage-datasets">Manage Datasets</div>
<div class="i18n-string" id="i18n-string-manage-datasets-text">Please calibrate the axes before managing datasets.</div>
<div class="i18n-string" id="i18n-string-can-not-delete-dataset">Can Not Delete!</div>
<div class="i18n-string" id="i18n-string-can-not-delete-dataset-text">You can not delete this dataset as at least one dataset is required.</div>
<div class="i18n-string" id="i18n-string-delete-dataset">Delete Dataset</div>
<div class="i18n-string" id="i18n-string-delete-dataset-text">You can not delete this dataset as at least one dataset is required.</div>
<div class="i18n-string" id="i18n-string-averaging-window">Averaging Window</div>
<div class="i18n-string" id="i18n-string-x-step-with-interpolation">X Step w/ Interpolation</div>
<div class="i18n-string" id="i18n-string-x-step">X Step</div>
<div class="i18n-string" id="i18n-string-blob-detector">Blob Detector</div>
<div class="i18n-string" id="i18n-string-bar-extraction">Bar Extraction</div>
<div class="i18n-string" id="i18n-string-histogram">Histogram</div>
<div class="i18n-string" id="i18n-string-specify-foreground-color">Specify Plot (Foreground) Color</div>
<div class="i18n-string" id="i18n-string-specify-background-color">Specify Background Color</div>




</body>
</html>