<!DOCTYPE HTML>
<html>
<?php session_start(); ?>
<head>
<script src="../jquery.js"></script>
<script src="webgl-debug.js"></script>
<script src="gl-matrix-min.js"></script>
<script id="vShader" type="x-shader/x-vertex">
	attribute vec3 aVertexPosition;
	attribute vec2 aTextureCoordinates;    
	varying vec2 vTextureCoordinates;
	uniform mat4 uMMatrix;
	uniform mat4 uPMatrix;
	
	void main() {
		gl_Position = uPMatrix * uMMatrix * vec4(aVertexPosition, 1.0);
		vTextureCoordinates = aTextureCoordinates;
	} 
</script>
<script id="fShader" type="x-shader/x-fragment">
	precision mediump float;
	uniform sampler2D uSampler;
	varying vec2 vTextureCoordinates;  
	void main() {
		gl_FragColor = texture2D(uSampler, vTextureCoordinates);
	}
</script>
<script>
var gl;
var canvas;
var shaderProgram;
var vertexBuffer;
var indexBuffer;

var stageCleared=0;

//textures variables
var colorfulTexture;
var woodTexture;
var chairTexture;
var roofTexture;
var textureBuffer;
var textureCoordinates;
var forestTexture;
var forestTexture2;
var plainTexture;
var darkgrassTexture;
var lavaTexture;
var lavagrassTexture;
var winTexture;

var tempMM=mat4.create();
var tempPM=mat4.create();
var tempVM=mat4.create();

//THESEIS,KTHRIA KAI ISTORIES
//var kthria= new Array(); //[[8esh,kthrio,upsos],[8esh,kthrio,upsos],...]
var building=new Array();
var locationsX=new Array();
var locationsY=new Array();
var locationsZ=new Array();
var ii=0; //posa kthria uparxoune sunolika
var cubeSize=5;
var worldSize=160;

var xx; //h 8esh pou 8eloume na valoume kthrio ston a3ona tou x (twra to vazoume sthn akrh tou skhnikou mas
var yy; //h 8esh pou 8eloume na valoume kthrio ston a3ona tou y
var zz=cubeSize/2;

//Where is camera and where does it look at
var kameraX=-30.00,kameraY=-30.00, kameraZ=cubeSize/2;
var lookX=0.00,lookZ=cubeSize/2;
//var for gravity
var gravityValue=0.4;
var gravityBoolean=0.0;

//vars for KEY EVENTS
var leftKeyIsPressed=0, rightKeyIsPressed=0, upKeyIsPressed=0, downKeyIsPressed=0;
var spaceKeyIsPressed=0;

//vars for MOUSE EVENTS
 var mouseDown = 0;
 var lastMouseX = null;
 var lastMouseZ = null;

//Cheese variables
var cheeseVertexBuffer;
var colorBuffer;
var cheeseIndexBuffer;
var cheeseTexture1,cheeseTexture2;
var cheeseRotatingAngle=0.00;
var cheesesLeft=0;

//Variables for textures currently in use
var currentWall=1;
var currentBuilding=1;

function createPointerBox(thesiX,thesiY,upsos)
{
	gl.activeTexture(gl.TEXTURE10);
	gl.bindTexture(gl.TEXTURE_2D, colorfulTexture); 
	gl.uniform1i(shaderProgram.uniformSamplerLoc, 10);	
	gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
	gl.bindBuffer(gl.ARRAY_BUFFER, vertexBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, vertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,indexBuffer);
	
			mat4.identity(tempMM);
			mat4.scale(tempMM,tempMM,[cubeSize,cubeSize,cubeSize]);
			mat4.translate(tempMM,tempMM,[thesiX,thesiY,upsos]);
			gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
			gl.drawElements(gl.TRIANGLES,36,gl.UNSIGNED_SHORT, 0);

//HOUSE DOOR
	// 33. Textures portas
	gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
	gl.vertexAttribPointer(shaderProgram.texturePositionAttribute, textureBuffer.itemSize, gl.FLOAT, false, 0, 0);	
}

//a simple bubble function to sort the array with the buildings-items 
//This function will compare and sort only the last value in the array
function sortArray()
{
	var i,temp;
	for(i=ii-1;i>0;i--)
	{
		if(locationsZ[i]<locationsZ[i-1])
		{
			temp=building[i];
			building[i]=building[i-1];
			building[i-1]=temp;
			temp=locationsX[i];
			locationsX[i]=locationsX[i-1];
			locationsX[i-1]=temp;
			temp=locationsY[i];
			locationsY[i]=locationsY[i-1];
			locationsY[i-1]=temp;
			temp=locationsZ[i];
			locationsZ[i]=locationsZ[i-1];
			locationsZ[i-1]=temp;
		}
	}
}

function createHouse(thesiX,thesiY,upsos,building)
{
	if(building==1)
	{
	gl.activeTexture(gl.TEXTURE0);
	gl.bindTexture(gl.TEXTURE_2D, wallTexture); 
	gl.uniform1i(shaderProgram.uniformSamplerLoc, 0);
	}
	else if(building==2)
	{
		gl.activeTexture(gl.TEXTURE0);
		gl.bindTexture(gl.TEXTURE_2D, woodTexture); 
		gl.uniform1i(shaderProgram.uniformSamplerLoc, 0);
	}
	else if(building==3)
	{
		gl.activeTexture(gl.TEXTURE0);
		gl.bindTexture(gl.TEXTURE_2D, lavaTexture); 
		gl.uniform1i(shaderProgram.uniformSamplerLoc, 0);
	}
	gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
	gl.bindBuffer(gl.ARRAY_BUFFER, vertexBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, vertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,indexBuffer);
	
			mat4.identity(tempMM);
			mat4.scale(tempMM,tempMM,[cubeSize,cubeSize,cubeSize]);
			mat4.translate(tempMM,tempMM,[thesiX,thesiY,upsos]);
			gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
			gl.drawElements(gl.TRIANGLES,30,gl.UNSIGNED_SHORT, 0);

//  //HOUSE DOOR
//  	// 33. Textures portas
//  	gl.activeTexture(gl.TEXTURE1);
//  	gl.bindTexture(gl.TEXTURE_2D, doorTexture); 
//  	gl.uniform1i(shaderProgram.uniformSamplerLoc, 1);	
//  	gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
//  	gl.vertexAttribPointer(shaderProgram.texturePositionAttribute, textureBuffer.itemSize, gl.FLOAT, false, 0, 0);
//  	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,indexBuffer);
	
			mat4.identity(tempMM);
			mat4.scale(tempMM,tempMM,[0.1,cubeSize,cubeSize]);
			mat4.translate(tempMM,tempMM,[-cubeSize/2+thesiX,0+thesiY,upsos]);
			gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
			gl.drawElements(gl.TRIANGLES,30,gl.UNSIGNED_SHORT, 0);
		
}
function createCastle(thesiX,thesiY,upsos)
{
// 	28. VAZOUME TA TEXTURES STO CASTLE
	gl.activeTexture(gl.TEXTURE2);
	gl.bindTexture(gl.TEXTURE_2D, wallTexture); 
	gl.uniform1i(shaderProgram.uniformSamplerLoc, 2);	
	gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
	gl.bindBuffer(gl.ARRAY_BUFFER, vertexBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, vertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,indexBuffer);

	//aplos toixos 1
		mat4.identity(tempMM);
		mat4.scale(tempMM,tempMM,[0.3,cubeSize,cubeSize]);
		mat4.translate(tempMM,tempMM,[cubeSize/2+thesiX,0+thesiY,upsos]);
		gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
		gl.drawElements(gl.TRIANGLES,indexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
	//aplos toixos 2
		mat4.identity(tempMM);
		mat4.scale(tempMM,tempMM,[0.3,cubeSize,cubeSize]);
		mat4.translate(tempMM,tempMM,[-cubeSize/2+thesiX,0+thesiY,upsos]);
		gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
		gl.drawElements(gl.TRIANGLES,indexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
	//aplos toixos 3
		mat4.identity(tempMM);
		mat4.scale(tempMM,tempMM,[cubeSize,0.3,cubeSize]);
		mat4.translate(tempMM,tempMM,[0+thesiX,-cubeSize/2+thesiY,upsos]);
		gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
		gl.drawElements(gl.TRIANGLES,indexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
	//tavani
		mat4.identity(tempMM);
		mat4.scale(tempMM,tempMM,[cubeSize,cubeSize,0.3]);
		mat4.translate(tempMM,tempMM,[thesiX,thesiY,upsos+cubeSize/2]);
		gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
		gl.drawElements(gl.TRIANGLES,indexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
	//Eisodos
		//gia na dimiourgh8ei i eisodos 8a xreiastoun 5 kuboi
		mat4.identity(tempMM);
		mat4.scale(tempMM,tempMM,[cubeSize*3/10,0.5,cubeSize/2]);
		mat4.translate(tempMM,tempMM,[cubeSize*3.5/10+thesiX,+cubeSize/2+thesiY,upsos-cubeSize/4]);
		gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
		gl.drawElements(gl.TRIANGLES,indexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
		
		mat4.identity(tempMM);
		mat4.scale(tempMM,tempMM,[cubeSize*3/10,0.5,cubeSize/2]);
		mat4.translate(tempMM,tempMM,[-cubeSize*3.5/10+thesiX,+cubeSize/2+thesiY,upsos-cubeSize/4]);
		gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
		gl.drawElements(gl.TRIANGLES,indexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
		
		mat4.identity(tempMM);
		mat4.scale(tempMM,tempMM,[cubeSize*3/10,0.5,cubeSize/2]);
		mat4.translate(tempMM,tempMM,[cubeSize*3.5/10+thesiX,+cubeSize/2+thesiY,upsos+cubeSize/4]);
		gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
		gl.drawElements(gl.TRIANGLES,indexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
		
		mat4.identity(tempMM);
		mat4.scale(tempMM,tempMM,[cubeSize*3/10,0.5,cubeSize/2]);
		mat4.translate(tempMM,tempMM,[-cubeSize*3.5/10+thesiX,+cubeSize/2+thesiY,upsos+cubeSize/4]);
		gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
		gl.drawElements(gl.TRIANGLES,indexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
		
		mat4.identity(tempMM);
		mat4.scale(tempMM,tempMM,[cubeSize*4/10,0.5,cubeSize/2]);
		mat4.translate(tempMM,tempMM,[0+thesiX,+cubeSize/2+thesiY,upsos+cubeSize/4]);
		gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
		gl.drawElements(gl.TRIANGLES,indexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
	//pesmenh porta
		//fortwse ta textures tou 3ulou
			gl.activeTexture(gl.TEXTURE3);
			gl.bindTexture(gl.TEXTURE_2D, tableTexture); 
			gl.uniform1i(shaderProgram.uniformSamplerLoc, 3);
			gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
			gl.vertexAttribPointer(shaderProgram.texturePositionAttribute, textureBuffer.itemSize, gl.FLOAT, false, 0, 0);
			gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,indexBuffer);
			
		mat4.identity(tempMM);
		mat4.scale(tempMM,tempMM,[cubeSize*6/10,cubeSize,cubeSize*0.2/10]);
		mat4.translate(tempMM,tempMM,[0+thesiX,+cubeSize+0.01+thesiY,0.1]);
		gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
		gl.drawElements(gl.TRIANGLES,indexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
}
//Cheese
function createCheese(thesiX,thesiY,upsos)
{
	//cheeseRotatingAngle=(cheeseRotatingAngle+3*Math.PI/180.0)%360.0;

	gl.activeTexture(gl.TEXTURE10);
	gl.bindTexture(gl.TEXTURE_2D, cheeseTexture1); 
	gl.uniform1i(shaderProgram.uniformSamplerLoc, 10);		

	gl.bindBuffer(gl.ARRAY_BUFFER, cheeseVertexBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, cheeseVertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,cheeseIndexBuffer);
			mat4.identity(tempMM);
			mat4.rotate(tempMM,tempMM,cheeseRotatingAngle, [0, 0, 1]);
			mat4.scale(tempMM,tempMM,[cubeSize/8,cubeSize/8,cubeSize/4]);
			mat4.translate(tempMM,tempMM,[thesiX,thesiY,upsos]);
			gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
			gl.drawElements(gl.TRIANGLES,36,gl.UNSIGNED_SHORT, 0);
			
	gl.activeTexture(gl.TEXTURE11);
	gl.bindTexture(gl.TEXTURE_2D, cheeseTexture2); 
	gl.uniform1i(shaderProgram.uniformSamplerLoc, 11);
			mat4.identity(tempMM);

			
			mat4.rotate(tempMM,tempMM,cheeseRotatingAngle, [0, 0, 1]);
			mat4.scale(tempMM,tempMM,[cubeSize/8,cubeSize/8,cubeSize/4]);
			mat4.translate(tempMM,tempMM,[thesiX,thesiY,upsos]);
			gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
			gl.drawElements(gl.TRIANGLES,18,gl.UNSIGNED_SHORT, 36*2);
}
function createFloor()
{
//FLOOR
	if(currentWall==1)
	{
		gl.activeTexture(gl.TEXTURE8);
		gl.bindTexture(gl.TEXTURE_2D, grassTexture); 
		gl.uniform1i(shaderProgram.uniformSamplerLoc, 8);	
		gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
		gl.bindBuffer(gl.ARRAY_BUFFER, floorVertexBuffer);
		gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, floorVertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,floorIndexBuffer);
	}
	else if(currentWall==3)
	{
		gl.activeTexture(gl.TEXTURE8);
		gl.bindTexture(gl.TEXTURE_2D, darkgrassTexture); 
		gl.uniform1i(shaderProgram.uniformSamplerLoc, 8);	
		gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
		gl.bindBuffer(gl.ARRAY_BUFFER, floorVertexBuffer);
		gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, floorVertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,floorIndexBuffer);
	}
	else if(currentWall==2)
	{
		gl.activeTexture(gl.TEXTURE0);
		gl.bindTexture(gl.TEXTURE_2D, wallTexture); 
		gl.uniform1i(shaderProgram.uniformSamplerLoc, 0);	
		gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
		gl.bindBuffer(gl.ARRAY_BUFFER, floorVertexBuffer);
		gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, floorVertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,floorIndexBuffer);
	}
	else if(currentWall==4)
	{
		gl.activeTexture(gl.TEXTURE8);
		gl.bindTexture(gl.TEXTURE_2D, lavagrassTexture); 
		gl.uniform1i(shaderProgram.uniformSamplerLoc, 8);	
		gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
		gl.bindBuffer(gl.ARRAY_BUFFER, floorVertexBuffer);
		gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, floorVertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,floorIndexBuffer);
	}
	mat4.identity(tempMM);
	gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM)); 
	gl.drawElements(gl.TRIANGLES,floorIndexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
}

function createWinBanner(thesiX,thesiY)
{
	if(stageCleared==1)
	{
	//Win or lose banner
			gl.activeTexture(gl.TEXTURE12);
			gl.bindTexture(gl.TEXTURE_2D, winTexture); 
			gl.uniform1i(shaderProgram.uniformSamplerLoc, 12);	
			gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
			gl.bindBuffer(gl.ARRAY_BUFFER, floorVertexBuffer);
			gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, floorVertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
			gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,floorIndexBuffer);
		mat4.identity(tempMM);
		//rotate the z axis according to where you are looking
		mat4.rotate(tempMM,tempMM,lookX, [0, 0, 1]);
		//rotate 90 rads in y (could have been in x axis as well with no differences but with different value)
		mat4.rotate(tempMM,tempMM,Math.PI/2, [0, 1, 0]);
		
		//Those two line are fixing the texture's appearance on screen. Because the texture appeared upside-down and completely reversed
			mat4.rotate(tempMM,tempMM,Math.PI, [0, 1, 0]);
			mat4.rotate(tempMM,tempMM,Math.PI*6/4, [0, 0, 1]);
	
		mat4.scale(tempMM,tempMM,[0.0011*40/worldSize,0.0011*40/worldSize,0]);
		mat4.translate(tempMM,tempMM,[thesiX,thesiY,kameraZ]);
		gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM)); 
		gl.drawElements(gl.TRIANGLES,floorIndexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
	}
}

function createSkybox()
{
// SKYBOX
	//If the wall has the forest them then don't draw the whole cube, so you must separate that specific choice
	if(currentWall==1)
	{
		gl.activeTexture(gl.TEXTURE9);
		gl.bindTexture(gl.TEXTURE_2D, skyTexture); 
		gl.uniform1i(shaderProgram.uniformSamplerLoc, 9);	
			gl.bindBuffer(gl.ARRAY_BUFFER, vertexBuffer);
			gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, vertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
			gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,indexBuffer);
				mat4.identity(tempMM);
				mat4.scale(tempMM,tempMM,[(worldSize+cubeSize*15),(worldSize+cubeSize*15),(worldSize+cubeSize*15)/2]);
				mat4.translate(tempMM,tempMM,[0,0,(worldSize+cubeSize*15)/4-1]);
				gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
				gl.drawElements(gl.TRIANGLES,6,gl.UNSIGNED_SHORT, 0);
				
				
			gl.activeTexture(gl.TEXTURE12);
			gl.bindTexture(gl.TEXTURE_2D, plainTexture); 
			gl.uniform1i(shaderProgram.uniformSamplerLoc, 12);	
				mat4.identity(tempMM);
				mat4.scale(tempMM,tempMM,[(worldSize+cubeSize*15),(worldSize+cubeSize*15),(worldSize+cubeSize*15)/2]);
				mat4.translate(tempMM,tempMM,[0,0,(worldSize+cubeSize*15)/4-1]);
				gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
				gl.drawElements(gl.TRIANGLES,30,gl.UNSIGNED_SHORT, 6*2);
	}
	else if(currentWall==2)
	{
		gl.activeTexture(gl.TEXTURE0);
		gl.bindTexture(gl.TEXTURE_2D, wallTexture); 
		gl.uniform1i(shaderProgram.uniformSamplerLoc, 0);	
		gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
		gl.bindBuffer(gl.ARRAY_BUFFER, vertexBuffer);
		gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, vertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,indexBuffer);
			mat4.identity(tempMM);
				mat4.scale(tempMM,tempMM,[worldSize,worldSize,worldSize/2]);
				mat4.translate(tempMM,tempMM,[0,0,worldSize/4-1]);
			gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
			gl.drawElements(gl.TRIANGLES,indexBuffer.numberOfItems,gl.UNSIGNED_SHORT, 0);
	}
	else if(currentWall==3)
	{
			gl.activeTexture(gl.TEXTURE9);
			gl.bindTexture(gl.TEXTURE_2D, forestTexture2); 
			gl.uniform1i(shaderProgram.uniformSamplerLoc, 9);
			gl.bindBuffer(gl.ARRAY_BUFFER, vertexBuffer);
			gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, vertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
			gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,indexBuffer);
				mat4.identity(tempMM);
				mat4.scale(tempMM,tempMM,[(worldSize+cubeSize*15),(worldSize+cubeSize*15),(worldSize+cubeSize*15)/2]);
				mat4.translate(tempMM,tempMM,[0,0,(worldSize+cubeSize*15)/4-1]);
				gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
				gl.drawElements(gl.TRIANGLES,6,gl.UNSIGNED_SHORT, 0);
				
				
			gl.activeTexture(gl.TEXTURE12);
			gl.bindTexture(gl.TEXTURE_2D, forestTexture); 
			gl.uniform1i(shaderProgram.uniformSamplerLoc, 12);	
				mat4.identity(tempMM);
				mat4.scale(tempMM,tempMM,[(worldSize+cubeSize*15),(worldSize+cubeSize*15),(worldSize+cubeSize*15)/2]);
				mat4.translate(tempMM,tempMM,[0,0,(worldSize+cubeSize*15)/4-1]);
				gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
				gl.drawElements(gl.TRIANGLES,30,gl.UNSIGNED_SHORT, 6*2);
	}
	else if(currentWall==4)
	{
			gl.activeTexture(gl.TEXTURE9);
			gl.bindTexture(gl.TEXTURE_2D, lavaTexture); 
			gl.uniform1i(shaderProgram.uniformSamplerLoc, 9);
			gl.bindBuffer(gl.ARRAY_BUFFER, vertexBuffer);
			gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, vertexBuffer.itemSize, gl.FLOAT, false, 0, 0);
			gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,indexBuffer);
				mat4.identity(tempMM);
				mat4.scale(tempMM,tempMM,[worldSize,worldSize,worldSize/2]);
				mat4.translate(tempMM,tempMM,[0,0,worldSize/4-1]);
				gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
				gl.drawElements(gl.TRIANGLES,6,gl.UNSIGNED_SHORT, 0);
				
				
			gl.activeTexture(gl.TEXTURE12);
			gl.bindTexture(gl.TEXTURE_2D, lavaTexture); 
			gl.uniform1i(shaderProgram.uniformSamplerLoc, 12);	
				mat4.identity(tempMM);
				mat4.scale(tempMM,tempMM,[worldSize,worldSize,worldSize/2]);
				mat4.translate(tempMM,tempMM,[0,0,worldSize/4-1]);
				gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));
				gl.drawElements(gl.TRIANGLES,30,gl.UNSIGNED_SHORT, 6*2);
	}
}

function createGLContext(canvas) {
	var context = null;

	context = canvas.getContext("webgl");
	if (!context)
		context = canvas.getContext("experimental-webgl");
	if (context) {
		context.viewportWidth = canvas.width;
		context.viewportHeight = canvas.height;
	}
	else {
		alert("Failed to create WebGL context!");
	}
	return context;
}

function loadShader(type, shaderSource) {
	var shader = gl.createShader(type);

	gl.shaderSource(shader, shaderSource);
	gl.compileShader(shader);

	if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
		alert("Error compiling shader" + gl.getShaderInfoLog(shader));
		gl.deleteShader(shader);
		return null;
	}
	return shader;
}
function setupShaders() {
	var vertexShaderSource = "";
	var vShaderScript=document.getElementById("vShader");

	var curChild=vShaderScript.firstChild;
	while (curChild) {
		if (curChild.nodeType==3)
			vertexShaderSource+=curChild.textContent;
		curChild=curChild.nextSibling;
	}

	var fragmentShaderSource = "";
	var fShaderScript=document.getElementById("fShader");
	curChild=fShaderScript.firstChild;
	while (curChild) {
		if (curChild.nodeType==3)
			fragmentShaderSource+=curChild.textContent;
		curChild=curChild.nextSibling;
	}

	var vertexShader = loadShader(gl.VERTEX_SHADER, vertexShaderSource);

	var fragmentShader = loadShader(gl.FRAGMENT_SHADER, fragmentShaderSource);

	shaderProgram = gl.createProgram();
	gl.attachShader(shaderProgram, vertexShader);
	gl.attachShader(shaderProgram, fragmentShader);
	gl.linkProgram(shaderProgram);

	if (!gl.getProgramParameter(shaderProgram, gl.LINK_STATUS)) {
		alert("Failed to setup shaders");
	}

	gl.useProgram(shaderProgram);

	shaderProgram.vertexPositionAttribute = gl.getAttribLocation(shaderProgram, "aVertexPosition");
	gl.enableVertexAttribArray(shaderProgram.vertexPositionAttribute);

	shaderProgram.texturePositionAttribute = gl.getAttribLocation(shaderProgram, "aTextureCoordinates");
	gl.enableVertexAttribArray(shaderProgram.texturePositionAttribute);


}

function setupBuffers() {

	shaderProgram.uMPosition = gl.getUniformLocation(shaderProgram, "uMMatrix");
	gl.uniformMatrix4fv(shaderProgram.uMPosition, false, new Float32Array(tempMM));

	shaderProgram.uPPosition = gl.getUniformLocation(shaderProgram, "uPMatrix");
	gl.uniformMatrix4fv(shaderProgram.uPPosition, false, new Float32Array(tempPM));

	shaderProgram.uniformSamplerLoc = gl.getUniformLocation(shaderProgram,"uSampler"); 
	
//Kuboi	
	vertexBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, vertexBuffer);
	var triangleVertices = [// Top face
							-0.5, -0.5,  0.5,
							0.5, -0.5,  0.5,
							0.5,  0.5,  0.5,
							-0.5,  0.5,  0.5,
							
							// Bottom face
							-0.5, -0.5,  -0.5,
							0.5, -0.5,  -0.5,
							0.5,  0.5,  -0.5,
							-0.5,  0.5,  -0.5,
							
							// Front face
							-0.5, 0.5, -0.5,
							0.5, 0.5, -0.5,
							0.5, 0.5,  0.5,
							-0.5, 0.5,  0.5,
							
							// Back face
							0.5, -0.5, -0.5,
							-0.5, -0.5, -0.5,
							-0.5, -0.5,  0.5,
							0.5, -0.5,  0.5,

							
							// Right face
							0.5, -0.5, -0.5,
							0.5,  0.5, -0.5,
							0.5,  0.5,  0.5,
							0.5, -0.5,  0.5,
							
							// Left face
							-0.5,  0.5, -0.5,
							-0.5, -0.5, -0.5,
							-0.5, -0.5,  0.5,
							-0.5,  0.5,  0.5,
];
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(triangleVertices),gl.STATIC_DRAW);
	vertexBuffer.itemSize = 3;
	vertexBuffer.numberOfItems = 24;

	indexBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,indexBuffer);
	var indexMatrix = 
					[0,  1,  2,      0,  2,  3,    // top
					4,  5,  6,      4,  6,  7,    // bottom
					8,  9,  10,     8,  10, 11,   // front
					12, 13, 14,     12, 14, 15,   // back
					16, 17, 18,     16, 18, 19,   // right
					20, 21, 22,     20, 22, 23];    // left
	gl.bufferData(gl.ELEMENT_ARRAY_BUFFER,new Uint16Array(indexMatrix),gl.STATIC_DRAW);
	indexBuffer.itemSize=1;
	indexBuffer.numberOfItems = 36;

//Floor
	floorVertexBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, floorVertexBuffer);
	var floorVertices = [-(worldSize+cubeSize*15)/2,-(worldSize+cubeSize*15)/2,-0.05,
						(worldSize+cubeSize*15)/2,-(worldSize+cubeSize*15)/2,-0.05,
						(worldSize+cubeSize*15)/2,(worldSize+cubeSize*15)/2,-0.05,
						-(worldSize+cubeSize*15)/2,(worldSize+cubeSize*15)/2,-0.05];
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(floorVertices),gl.STATIC_DRAW);
	floorVertexBuffer.itemSize = 3;
	floorVertexBuffer.numberOfItems = 4;
	
	floorIndexBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,floorIndexBuffer);
	var floorIndexMatrix = [0,1,2,
							0,2,3];
	gl.bufferData(gl.ELEMENT_ARRAY_BUFFER,new Uint16Array(floorIndexMatrix),gl.STATIC_DRAW);   
	floorIndexBuffer.itemSize=1;   
	floorIndexBuffer.numberOfItems = 6;  

	
	textureBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, textureBuffer);
	textureCoordinates=[    // TOP
    0.0,  0.0,
    1.0,  0.0,
    1.0,  1.0,
    0.0,  1.0,
    // Bottom
    0.0,  0.0,
    1.0,  0.0,
    1.0,  1.0,
    0.0,  1.0,
    // front
    0.0,  0.0,
    1.0,  0.0,
    1.0,  1.0,
    0.0,  1.0,
    // Back
    0.0,  0.0,
    1.0,  0.0,
    1.0,  1.0,
    0.0,  1.0,
    // Right
    0.0,  0.0,
    1.0,  0.0,
    1.0,  1.0,
    0.0,  1.0,
    //Left
    0.0,  0.0,
    1.0,  0.0,
    1.0,  1.0,
    0.0,  1.0,
    // upper
    0.0,  0.0,
    1.0,  0.0,
    1.0,  1.0,
    0.0,  1.0,
    // lower
    0.0,  0.0,
    1.0,  0.0,
    1.0,  1.0,
    0.0,  1.0,
    // left
    0.0,  0.0,
    1.0,  0.0,
    1.0,  1.0,
    0.0,  1.0,
    // right
    0.0,  0.0,
    1.0,  0.0,
    1.0,  1.0,
    0.0,  1.0,
  ];
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoordinates),gl.STATIC_DRAW);  
	textureBuffer.itemSize = 2;
	textureBuffer.numberOfItems = 40;
	
	
//CHEESE
	cheeseVertexBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, cheeseVertexBuffer);
	var cheeseVertices = [// Front face
							-0.5, -1.5,  0.5,
							0.5, -1.5,  0.5,
							0.5,  -0.5,  0.5,
							-0.5,  -0.5,  0.5,
							
							// Back face
							-0.5, -1.5,  -0.5,
							0.5, -1.5,  -0.5,
							0.5,  -0.5,  -0.5,
							-0.5,  -0.5,  -0.5,
							
							// Top face
							-0.5, -0.5, -0.5,
							0.5, -0.5, -0.5,
							0.5, -0.5,  0.5,
							-0.5, -0.5,  0.5,
							
							// Bottom face
							-0.5, -1.5, -0.5,
							0.5, -1.5, -0.5,
							0.5, -1.5,  0.5,
							-0.5, -1.5,  0.5,
							
							// Right face
							0.5, -1.5, -0.5,
							0.5,  -0.5, -0.5,
							0.5,  -0.5,  0.5,
							0.5, -1.5,  0.5,
							
							// Left face
							-0.5, -1.5, -0.5,
							-0.5,  -0.5, -0.5,
							-0.5,  -0.5,  0.5,
							-0.5, -1.5,  0.5,
							//Tyri upper face
							0.5,-0.5,0.5,
							-0.5,-0.5,0.5,
							0,1.4,0.4,
							//Tyri lower face
							0.5,-0.5,-0.5,
							-0.5,-0.5,-0.5,
							0,1.4,-0.4,
							//Tyri left face
							-0.5,-0.5,0.5,
							-0.5,-0.5,-0.5,
							0,1.4,0.4,
							0,1.4,-0.4,
							//Tyri right face
							0.5,-0.5,0.5,
							0.5,-0.5,-0.5,
							0,1.4,0.4,
							0,1.4,-0.4];
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(cheeseVertices),gl.STATIC_DRAW);
	cheeseVertexBuffer.itemSize = 3;
	cheeseVertexBuffer.numberOfItems = 38;
	
	colorBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, colorBuffer);
	triangleColors=[1,0,0,1,
						0,1,0,1,
						0,0,1,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1,
						1,1,0,1];
	gl.bufferData(gl.ARRAY_BUFFER,new Float32Array(triangleColors),gl.STATIC_DRAW);
	colorBuffer.itemSize = 4;
	colorBuffer.numberOfItems=38;

	cheeseIndexBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER,cheeseIndexBuffer);
	var indexCheese = 
					[0,  1,  2,      0,  2,  3,    // front
					4,  5,  6,      4,  6,  7,    // back
					8,  9,  10,     8,  10, 11,   // top
					12, 13, 14,     12, 14, 15,   // bottom
					16, 17, 18,     16, 18, 19,   // right
					20, 21, 22,     20, 22, 23,   // left
					24,25,26, //cheese upper face
					27,28,29, //cheese lower face
					30,31,33, 30,32,33, //cheese left face
					34,35,37, 34,36,37  //cheese right face
					];    
	gl.bufferData(gl.ELEMENT_ARRAY_BUFFER,new Uint16Array(indexCheese),gl.STATIC_DRAW);
	cheeseIndexBuffer.itemSize=1;
	cheeseIndexBuffer.numberOfItems = 54;
//TELOS TYRIOU
	
		woodTexture = gl.createTexture();
	var textureUrl = "wood.jpg";
	loadImageForTexture(textureUrl, woodTexture);
	
	// ...OMOIA ΓΙΑ ΤΟ TEXTURE H KAREKLA
	chairTexture = gl.createTexture();
	textureUrl = "fabric.jpg";
	loadImageForTexture(textureUrl, chairTexture);
	
	// ...OMOIA ΓΙΑ ΤΟ TEXTURE TOY SKY
	skyTexture = gl.createTexture();
	textureUrl = "sky.jpg";
	loadImageForTexture(textureUrl, skyTexture);
	
	// ...FOTOGRAFIA MA8HTWN
	ma8htesTexture = gl.createTexture();
	textureUrl = "ma8htes.jpg";
	loadImageForTexture(textureUrl, ma8htesTexture);
	
	// ...OMOIA ΓΙΑ ΤΟ TEXTURE TON TOIXO TOU SPITIOU
	wallTexture = gl.createTexture();
	textureUrl = "stone.jpg";
	loadImageForTexture(textureUrl, wallTexture);
	
	// ...OMOIA ΓΙΑ ΤΟ TEXTURE THS PORTAS
	doorTexture = gl.createTexture();
	textureUrl = "door.jpg";
	loadImageForTexture(textureUrl, doorTexture);
	
	// ...OMOIA ΓΙΑ ΤΟ TEXTURE TO GRASIDI
	grassTexture = gl.createTexture();
	textureUrl = "grass.jpg";
	loadImageForTexture(textureUrl, grassTexture);

	// ...OMOIA ΓΙΑ ΤΟ TEXTURE GIA TA KERAMIDIA
	roofTexture = gl.createTexture();
	textureUrl = "roof.jpg";
	loadImageForTexture(textureUrl, roofTexture);
	
	//OMOIA GIA TO POINTERBOX
	colorfulTexture = gl.createTexture();
	textureUrl = "colorful.jpg";
	loadImageForTexture(textureUrl, colorfulTexture);
	
	//OMOIA GIA TO CHEESE
	cheeseTexture1=gl.createTexture();
	textureUrl="Cheese1.jpg";
	loadImageForTexture(textureUrl, cheeseTexture1);
	
	cheeseTexture2=gl.createTexture();
	textureUrl="Cheese2.jpg";
	loadImageForTexture(textureUrl, cheeseTexture2);
	
	//OMOIA GIA TO FOREST
	forestTexture = gl.createTexture();
	textureUrl = "forest.jpg";
	loadImageForTexture(textureUrl, forestTexture);
	
	forestTexture2 = gl.createTexture();
	textureUrl = "forest2.jpg";
	loadImageForTexture(textureUrl, forestTexture2);
	
	//Textures for the plains
	plainTexture = gl.createTexture();
	textureUrl = "plains.jpg";
	loadImageForTexture(textureUrl, plainTexture);
	
	//Dark grass
	darkgrassTexture = gl.createTexture();
	textureUrl = "darkgrass.jpg";
	loadImageForTexture(textureUrl, darkgrassTexture);
	
	//lava
	lavaTexture = gl.createTexture();
	textureUrl = "lava.jpg";
	loadImageForTexture(textureUrl, lavaTexture);
	
	lavagrassTexture = gl.createTexture();
	textureUrl = "lavagrass.jpg";
	loadImageForTexture(textureUrl, lavagrassTexture);
	
	//win Banner TEXTURE
	winTexture = gl.createTexture();
	textureUrl = "winBanner.png";
	loadImageForTexture(textureUrl, winTexture);
}

function loadImageForTexture(url, texturePar) {  
	var image = new Image();  
	image.onload = function() {    
		textureFinishedLoading(image, texturePar);  
	} 
	image.src = url;
}

function textureFinishedLoading(im,txtr) {
	gl.bindTexture(gl.TEXTURE_2D, txtr);
	gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
	gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, im);
	gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
	gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR_MIPMAP_NEAREST);
	gl.generateMipmap(gl.TEXTURE_2D);
}

function startup() {
	canvas = document.getElementById("myGLCanvas");
	var minDim=Math.min(window.innerWidth,window.innerHeight);
	canvas.width=1*minDim;
	canvas.height=0.8*minDim;
	canvas.style.width  = '50em';
	canvas.style.height = '40em';

	gl = WebGLDebugUtils.makeDebugContext(createGLContext(canvas));
	setupShaders();
	setupBuffers();
	gl.enable(gl.DEPTH_TEST);
	gl.blendFunc(gl.SRC_ALPHA, gl.ONE_MINUS_SRC_ALPHA);
	gl.enable(gl.BLEND);
	
	
	gl.clearColor(0.5, 0.5, 0.5, 1.0);
	gl.viewport(0, 0, gl.viewportWidth, gl.viewportHeight);
	gl.clear(gl.COLOR_BUFFER_BIT);
	gl.clear(gl.DEPTH_BUFFER_BIT);
	
	//FOR TRIAL ONLY
	//create an array sample filled with objects
	//ii=22;
	//building[0]=1;
	//building[1]=1;
	//building[2]=1;
	//building[3]=1;
	//building[4]=1;
	//building[5]=1;
	//building[6]=1;
	//building[7]=1;
	//building[8]=1;
	//building[9]=1;
	//building[10]=1;
	//building[11]=1;
	//locationsX[0]=cubeSize;
	//locationsY[0]=0;
	//locationsZ[0]=cubeSize/2;
	//	locationsX[1]=-cubeSize
	//locationsY[1]=0
	//locationsZ[1]=cubeSize/2;
	//	locationsX[2]=0
	//locationsY[2]=cubeSize
	//locationsZ[2]=cubeSize/2;
	//	locationsX[3]=0
	//locationsY[3]=-cubeSize
	//locationsZ[3]=cubeSize/2;
	//	locationsX[4]=cubeSize
	//locationsY[4]=cubeSize
	//locationsZ[4]=cubeSize/2;
	//	locationsX[5]=-cubeSize
	//locationsY[5]=cubeSize
	//locationsZ[5]=cubeSize/2;
	//	locationsX[6]=cubeSize
	//locationsY[6]=-cubeSize
	//locationsZ[6]=cubeSize/2;
	//	locationsX[7]=-cubeSize
	//locationsY[7]=-cubeSize
	//locationsZ[7]=cubeSize/2;
	//	locationsX[8]=cubeSize
	//locationsY[8]=cubeSize
	//locationsZ[8]=cubeSize/2+cubeSize;
	//	locationsX[9]=cubeSize
	//locationsY[9]=-cubeSize
	//locationsZ[9]=cubeSize/2+cubeSize;
	//	locationsX[10]=-cubeSize
	//locationsY[10]=cubeSize
	//locationsZ[10]=cubeSize/2+cubeSize;
	//	locationsX[11]=-cubeSize
	//locationsY[11]=-cubeSize
	//locationsZ[11]=cubeSize/2+cubeSize;
	//
	//building[12]=2;
	//locationsX[12]=cubeSize*3;
	//locationsY[12]=0;
	//locationsZ[12]=cubeSize/2;
	//building[13]=2;
	//locationsX[13]=cubeSize*3;
	//locationsY[13]=0;
	//locationsZ[13]=cubeSize/2+cubeSize;
	//building[14]=10;
	//locationsX[14]=cubeSize*3;
	//locationsY[14]=0;
	//locationsZ[14]=cubeSize/2+cubeSize+cubeSize;
	//
	//building[15]=3;
	//locationsX[15]=cubeSize;
	//locationsY[15]=0;
	//locationsZ[15]=cubeSize/2+cubeSize*3;
	//building[16]=10;
	//locationsX[16]=cubeSize;
	//locationsY[16]=0;
	//locationsZ[16]=cubeSize/2+cubeSize*4;	
	//
	//building[17]=3;
	//locationsX[17]=cubeSize*3;
	//locationsY[17]=cubeSize;
	//locationsZ[17]=cubeSize/2+cubeSize*2;
	//building[18]=10;
	//locationsX[18]=cubeSize*3;
	//locationsY[18]=cubeSize;
	//locationsZ[18]=cubeSize/2+cubeSize*3;
	//
	//building[19]=3;
	//locationsX[19]=cubeSize*3;
	//locationsY[19]=cubeSize+cubeSize;
	//locationsZ[19]=cubeSize/2+cubeSize*3;
	//building[20]=10;
	//locationsX[20]=cubeSize*3;
	//locationsY[20]=cubeSize*2;
	//locationsZ[20]=cubeSize/2+cubeSize*4;
	//building[21]=10;
	//locationsX[21]=cubeSize*3;
	//locationsY[21]=cubeSize*2;
	//locationsZ[21]=cubeSize/2+cubeSize*5;
	//cheesesLeft=5;
	anim(); 

}

function redraw() {
	setUpZZ();
	whichKeyIsPressed();
	gravity();
	cheeseRotatingAngle=(cheeseRotatingAngle+3*Math.PI/180.0)%360.0;
	
	
	var br,bg;
	var i;
	br=0.95;
	bg=0.95;
	bb=0.95;
	
	gl.clearColor(br, bg, bb, 1.0);
	gl.clear(gl.COLOR_BUFFER_BIT);
	gl.clear(gl.DEPTH_BUFFER_BIT);

	mat4.lookAt(tempVM, [kameraX, kameraY, kameraZ], [kameraX+Math.cos(lookX)*cubeSize,kameraY+Math.sin(lookX)*cubeSize, lookZ], [0, 0, 1]);
    mat4.perspective(tempPM, Math.PI/2, 1, 0.01, worldSize+worldSize);
    mat4.multiply(tempMM, tempPM, tempVM);
    gl.uniformMatrix4fv(shaderProgram.uPPosition, false, new Float32Array(tempMM));

createSkybox();	
createFloor();
	for(i=0;i<ii;i++)
	{
		if(building[i]==1 || building[i]==2 || building[i]==3)
		{
			createHouse(locationsX[i],locationsY[i],locationsZ[i],building[i]);
		}
		else if(building[i]==10)
		{
			createCheese(locationsX[i],locationsY[i],locationsZ[i]);
		}
	}
if(kameraX+Math.cos(lookX)*2*cubeSize<= -worldSize/2+(cubeSize/2+0.1)) xx=-worldSize/2+(cubeSize/2+0.1);
else if(kameraX+Math.cos(lookX)*2*cubeSize>= worldSize/2-(cubeSize/2+0.1)) xx=worldSize/2-(cubeSize/2+0.1);
else xx=kameraX+Math.cos(lookX)*2*cubeSize;
if(yy=kameraY+Math.sin(lookX)*2*cubeSize<= -worldSize/2+(cubeSize/2+0.1)) yy=-worldSize/2+(cubeSize/2+0.1);
else if(yy=kameraY+Math.sin(lookX)*2*cubeSize> worldSize/2-(cubeSize/2+0.1)) yy=worldSize/2-(cubeSize/2+0.1);
else yy=kameraY+Math.sin(lookX)*2*cubeSize;

createPointerBox(xx,yy,zz);
createWinBanner(kameraX+Math.cos(lookX)*0.01*cubeSize,kameraY+Math.sin(lookX)*0.01*cubeSize);

}

function setNewBuilding(kthrio)
{
	if(zz<(worldSize/2-1)-cubeSize/2)
	{
		locationsX[ii]=xx;
		locationsY[ii]=yy;
		building[ii]=kthrio;
		locationsZ[ii]=zz;
		ii++;
	
//Now we need to sort the array, because of troubles when creating objects with lower Z than the existing ones
	sortArray();
	}
	
	//if new building is cheese then cheeseLeft are increasing
	if(kthrio==10)
	{
		cheesesLeft=cheesesLeft+1;
	}
}


//Set Up zz!  The zz is the height of the pointerBox
function setUpZZ()
{
	var tempCubeSize=cubeSize-(cubeSize*0.1);
	
	if(lookZ<cubeSize/2) zz=cubeSize/2;  //an ekei pou koitame einai katw apo tin gh
	else if(lookZ>(worldSize/2-1)-cubeSize/2) zz=(worldSize/2-1)-cubeSize/2; //an ekei pou koitame einai panw apo thn gh
	else zz=lookZ; //an ekei pou koitame einai komple
	
	for(i=0;i<ii;i++)
	{
		if(xx-locationsX[i]>-tempCubeSize && xx-locationsX[i]<tempCubeSize && yy-locationsY[i]<tempCubeSize && yy-locationsY[i]>-tempCubeSize && zz-locationsZ[i]<tempCubeSize && zz-locationsZ[i]>-tempCubeSize)
		{
			zz=locationsZ[i]+cubeSize;
			//gia kapio logo palia to eixa etsi... giati arage? (to prwto if htan ka8ara tou paliou tropou alla to 2o?
			//if(xx-locationsX[i]>-cubeSize && xx-locationsX[i]<cubeSize && yy-locationsY[i]<cubeSize && yy-locationsY[i]>-cubeSize && zz-locationsZ[i]<cubeSize/2 && zz-locationsZ[i]>-cubeSize/2)
				//if(zz<=locationsZ[i]) { zz=locationsZ[i]+cubeSize; }
		}
	}
}

//KEYBOARD EVENT HANDLERS
//		IF FLAG IS ON
function whichKeyIsPressed()
{
	if(leftKeyIsPressed==1)
	{
		//turn camera left
		if(mouseDown==0)
		{
			lookX=(lookX+3*Math.PI/180.0)%360.0;
		}
		//move left
		else
		{
			//check for possible collision
			var i;
			var tempLookX;
			var forwardSpeed=0.25;
			tempLookX=(lookX+90*Math.PI/180.0)%360.0;
			kameraX=kameraX+forwardSpeed*(Math.cos(tempLookX)); // h kamera na kini8ei ston a3ona twn x kata cos(pros ekei pou koitaei + 90 moires)
			kameraY=kameraY+forwardSpeed*(Math.sin(tempLookX)); // h kamera na kini8ei ston a3ona twn y kata sin(pros ekei pou koitaei + 90 moires)
			if(kameraX<-(worldSize/2-1) || kameraX>(worldSize/2-1)) kameraX=kameraX-forwardSpeed*(Math.cos(tempLookX)); //anairese thn kinhsh tou x
			if(kameraY<-(worldSize/2-1) || kameraY>(worldSize/2-1)) kameraY=kameraY-forwardSpeed*(Math.sin(tempLookX)); //anairese thn kinhsh tou y
			for(i=0;i<ii;i++)
			{
				//cube collision
				if(building[i]==1 || building[i]==2 || building[i]==3)
				{
					if(kameraX-locationsX[i]>-(cubeSize/2+forwardSpeed) && kameraX-locationsX[i]<(cubeSize/2+forwardSpeed) && kameraY-locationsY[i]<(cubeSize/2+forwardSpeed) && kameraY-locationsY[i]>-(cubeSize/2+forwardSpeed) && kameraZ-locationsZ[i]<cubeSize/2 && kameraZ-locationsZ[i]>-cubeSize/2)  
					{
						kameraX=kameraX-forwardSpeed*(Math.cos(tempLookX));
						kameraY=kameraY-forwardSpeed*(Math.sin(tempLookX));
					}
				}
				//cheese collision
				//else if(building[i]==10)
				//{
				//	if(kameraX-locationsX[i]>-(cubeSize/3+forwardSpeed) && kameraX-locationsX[i]<(cubeSize/3+forwardSpeed) && kameraY-locationsY[i]<(cubeSize/3+forwardSpeed) && kameraY-locationsY[i]>-(cubeSize/3+forwardSpeed) && kameraZ-locationsZ[i]<cubeSize/2 && kameraZ-locationsZ[i]>-cubeSize/2)  
				//	{
				//		cheesesLeft=cheesesLeft-1;
				//		building[i]=0;
				//		if(cheesesLeft==0) stageCleared=1;
				//	}
				//}
			}
		}
	}
	if(rightKeyIsPressed==1)
	{
		if(mouseDown==0)
		{
			lookX=(lookX-3*Math.PI/180.0)%360.0;
		}
		else
		{
			//check for possible collision
			var i;
			var tempLookX;
			var forwardSpeed=0.25;
			tempLookX=(lookX-90*Math.PI/180.0)%360.0;
			kameraX=kameraX+forwardSpeed*(Math.cos(tempLookX)); // h kamera na kini8ei ston a3ona twn x kata cos(pros ekei pou koitaei + 90 moires)
			kameraY=kameraY+forwardSpeed*(Math.sin(tempLookX)); // h kamera na kini8ei ston a3ona twn y kata sin(pros ekei pou koitaei + 90 moires)
			if(kameraX<-(worldSize/2-1) || kameraX>(worldSize/2-1)) kameraX=kameraX-forwardSpeed*(Math.cos(tempLookX)); //anairese thn kinhsh tou x
			if(kameraY<-(worldSize/2-1) || kameraY>(worldSize/2-1)) kameraY=kameraY-forwardSpeed*(Math.sin(tempLookX)); //anairese thn kinhsh tou y
			for(i=0;i<ii;i++)
			{
				//cube collision
				if(building[i]==1 || building[i]==2 || building[i]==3)
				{
					if(kameraX-locationsX[i]>-(cubeSize/2+forwardSpeed) && kameraX-locationsX[i]<(cubeSize/2+forwardSpeed) && kameraY-locationsY[i]<(cubeSize/2+forwardSpeed) && kameraY-locationsY[i]>-(cubeSize/2+forwardSpeed) && kameraZ-locationsZ[i]<cubeSize/2 && kameraZ-locationsZ[i]>-cubeSize/2)  
					{
						kameraX=kameraX-forwardSpeed*(Math.cos(tempLookX));
						kameraY=kameraY-forwardSpeed*(Math.sin(tempLookX));
					}
				}
				//cheese collision
				//else if(building[i]==10)
				//{
				//	if(kameraX-locationsX[i]>-(cubeSize/3+forwardSpeed) && kameraX-locationsX[i]<(cubeSize/3+forwardSpeed) && kameraY-locationsY[i]<(cubeSize/3+forwardSpeed) && kameraY-locationsY[i]>-(cubeSize/3+forwardSpeed) && kameraZ-locationsZ[i]<cubeSize/2 && kameraZ-locationsZ[i]>-cubeSize/2)  
				//	{
				//		cheesesLeft=cheesesLeft-1;
				//		building[i]=0;
				//		if(cheesesLeft==0) stageCleared=1;
				//	}
				//}
			}
		}
	}
	if(upKeyIsPressed==1 || mouseDown==2)
	{
		var i;
		var forwardSpeed=0.25;
		kameraX=kameraX+forwardSpeed*(Math.cos(lookX)); // h kamera na kini8ei ston a3ona twn x kata cos(pros ekei pou koitaei)
		kameraY=kameraY+forwardSpeed*(Math.sin(lookX)); // h kamera na kini8ei ston a3ona twn y kata sin(pros ekei pou koitaei)
		if(kameraX<-(worldSize/2-1) || kameraX>(worldSize/2-1)) kameraX=kameraX-forwardSpeed*(Math.cos(lookX)); //anairese thn kinhsh tou x
		if(kameraY<-(worldSize/2-1) || kameraY>(worldSize/2-1)) kameraY=kameraY-forwardSpeed*(Math.sin(lookX)); //anairese thn kinhsh tou y
		for(i=0;i<ii;i++)
		{
			//cube collision
			if(building[i]==1 || building[i]==2 || building[i]==3)
			{
				if(kameraX-locationsX[i]>-(cubeSize/2+forwardSpeed) && kameraX-locationsX[i]<(cubeSize/2+forwardSpeed) && kameraY-locationsY[i]<(cubeSize/2+forwardSpeed) && kameraY-locationsY[i]>-(cubeSize/2+forwardSpeed) && kameraZ-locationsZ[i]<cubeSize/2 && kameraZ-locationsZ[i]>-cubeSize/2)  
				{
					//the first if is to avoid the bug on the cube's edges
					if(Math.abs(kameraX-locationsX[i])-Math.abs(kameraY-locationsY[i])<forwardSpeed/2.5 && Math.abs(kameraX-locationsX[i])-Math.abs(kameraY-locationsY[i])>-forwardSpeed/2.5)
					{
						kameraY=kameraY-forwardSpeed*(Math.sin(lookX));
						kameraX=kameraX-forwardSpeed*(Math.cos(lookX));
					}
					if(Math.abs(kameraX-locationsX[i])>Math.abs(kameraY-locationsY[i]))
					{
						kameraX=kameraX-forwardSpeed*(Math.cos(lookX));
					}
					else if(Math.abs(kameraX-locationsX[i])<Math.abs(kameraY-locationsY[i]))
					{
						kameraY=kameraY-forwardSpeed*(Math.sin(lookX));
					}
					else
					{
						kameraY=kameraY-forwardSpeed*(Math.sin(lookX));
						kameraX=kameraX-forwardSpeed*(Math.cos(lookX));
					}
				}
			}
			//cheese collision
			//else if(building[i]==10)
			//{
			//	if(kameraX-locationsX[i]>-(cubeSize/3+forwardSpeed) && kameraX-locationsX[i]<(cubeSize/3+forwardSpeed) && kameraY-locationsY[i]<(cubeSize/3+forwardSpeed) && kameraY-locationsY[i]>-(cubeSize/3+forwardSpeed) && kameraZ-locationsZ[i]<cubeSize/2 && kameraZ-locationsZ[i]>-cubeSize/2)  
			//	{
			//	cheesesLeft=cheesesLeft-1;
			//	building[i]=0;
			//	if(cheesesLeft==0) stageCleared=1;
			//	}
			//}
//A dokimh
//		{
//			if(Math.abs(Math.cos(lookX))<Math.abs(Math.sin(lookX)))
//			{
//				kameraY=kameraY-0.25*(Math.sin(lookX));
//			}
//			else if(Math.abs(Math.cos(lookX))>Math.abs(Math.sin(lookX)))
//			{
//				kameraX=kameraX-0.25*(Math.cos(lookX));
//			}
//			else
//			{
//				kameraY=kameraY-0.25*(Math.sin(lookX));
//				kameraX=kameraX-0.25*(Math.cos(lookX));
//			}
//		}
//B DOKIMH
//		if(Math.abs(kameraX-kameraY)<0.5)
//		{
//			kameraY=kameraY-0.25*(Math.sin(lookX));
//			kameraX=kameraX-0.25*(Math.cos(lookX));
//		}
//		else if(Math.abs(kameraX)>Math.abs(kameraY)) //an h kinhsh gernei pio polu pros ton a3ona twn X tote anairesai mono thn kinhsh tou y
//		{
//			kameraX=kameraX-0.25*(Math.cos(lookX));
//		}
//		else if(Math.abs(kameraX)<Math.abs(kameraY))
//		{
//			kameraY=kameraY-0.25*(Math.sin(lookX));
//		}
//		else
//		{
//			kameraY=kameraY-0.25*(Math.sin(lookX));
//			kameraX=kameraX-0.25*(Math.cos(lookX));
//		}
//	}
		}
	}
	if(downKeyIsPressed==1)
	{
		var backwardMovement=0.06;
		kameraX=kameraX-backwardMovement*(Math.cos(lookX));
		kameraY=kameraY-backwardMovement*(Math.sin(lookX));
		if(kameraX<-(worldSize/2-1) || kameraX>(worldSize/2-1)) kameraX=kameraX+backwardMovement*(Math.cos(lookX)); //anairese thn kinhsh tou x
		if(kameraY<-(worldSize/2-1) || kameraY>(worldSize/2-1)) kameraY=kameraY+backwardMovement*(Math.sin(lookX)); //anairese thn kinhsh tou y
		for(i=0;i<ii;i++)
		{
			//cube collision
			if(building[i]==1 || building[i]==2 || building[i]==3)
			{
				if(kameraX-locationsX[i]>-(cubeSize/2+backwardMovement) && kameraX-locationsX[i]<(cubeSize/2+backwardMovement) && kameraY-locationsY[i]<(cubeSize/2+backwardMovement) && kameraY-locationsY[i]>-(cubeSize/2+backwardMovement) && kameraZ-locationsZ[i]<cubeSize/2 && kameraZ-locationsZ[i]>-cubeSize/2)
				{
					if(Math.abs(kameraX-locationsX[i])-Math.abs(kameraY-locationsY[i])<backwardMovement/2.5 && Math.abs(kameraX-locationsX[i])-Math.abs(kameraY-locationsY[i])>-backwardMovement/2.5)
					{
						kameraY=kameraY+backwardMovement*(Math.sin(lookX));
						kameraX=kameraX+backwardMovement*(Math.cos(lookX));
					}
					if(Math.abs(kameraX-locationsX[i])>Math.abs(kameraY-locationsY[i]))
					{
						kameraX=kameraX+backwardMovement*(Math.cos(lookX));
					}
					else if(Math.abs(kameraX-locationsX[i])<Math.abs(kameraY-locationsY[i]))
					{
						kameraY=kameraY+backwardMovement*(Math.sin(lookX));
					}
					else
					{
						kameraY=kameraY+backwardMovement*(Math.sin(lookX));
						kameraX=kameraX+backwardMovement*(Math.cos(lookX));
					}
				}
			}
			//cheese collision
			//else if(building[i]==10)
			//{
			//	if(kameraX-locationsX[i]>-(cubeSize/3+backwardMovement) && kameraX-locationsX[i]<(cubeSize/3+backwardMovement) && kameraY-locationsY[i]<(cubeSize/3+backwardMovement) && kameraY-locationsY[i]>-(cubeSize/3+backwardMovement) && kameraZ-locationsZ[i]<cubeSize/2 && kameraZ-locationsZ[i]>-cubeSize/2)  
			//	{
			//		cheesesLeft=cheesesLeft-1;
			//		building[i]=0;
			//		if(cheesesLeft==0) stageCleared=1;
			//	}
			//}
//Other fail-try
//			if(Math.abs(Math.cos(lookX))<Math.abs(Math.sin(lookX))) //an h kinhsh gernei pio polu pros ton a3ona twn X tote anairesai mono thn kinhsh tou y
//			{
//				kameraY=kameraY+0.15*(Math.sin(lookX));
//			}
//			else if(Math.abs(Math.sin(lookX))<Math.abs(Math.cos(lookX))) //an h kinhsh gernei pio polu pros ton a3ona twn y tote anairesai mono thn kinhsh tou x
//			{
//				kameraX=kameraX+0.15*(Math.cos(lookX)); //anairese thn kinhsh tou x
//			}
//			else
//			{
//				kameraY=kameraY+0.15*(Math.sin(lookX));
//				kameraX=kameraX+0.15*(Math.cos(lookX));
//			}
		}
	}
}

function gravity()
{
	gravityBoolean=gravityBoolean+gravityValue;
	//Possible collisions of Z -- KAPOU PATAEI H XTUPAEI TO KEFALI TOU OTAN PROSPA8EI NA PHDH3EI
	if(kameraZ<cubeSize/2) { kameraZ=cubeSize/2; lookZ=lookZ+gravityValue; gravityBoolean=0;}
	if(kameraZ>(worldSize/2-1)-cubeSize/2) { kameraZ=(worldSize/2-1)-cubeSize/2; gravityBoolean=0;}
	
	for(i=0;i<ii;i++)
	{
			if(building[i]==1 || building[i]==2 || building[i]==3)
		{
			//if the kamera is coming from bellow. There is a small invisible wall bellow the cube which is triggered before the cube's collision
			if(kameraX-locationsX[i]>-(cubeSize/2+0.05) && kameraX-locationsX[i]<(cubeSize/2+0.05) && kameraY-locationsY[i]<(cubeSize/2+0.05) && kameraY-locationsY[i]>-(cubeSize/2+0.05) && kameraZ-locationsZ[i]<0 && kameraZ-locationsZ[i]>-cubeSize/2)
			{
				spaceKeyIsPressed=0;
			}
			// If the kamera isn't coming from bellow
			else if(kameraX-locationsX[i]>-(cubeSize/2+0.05) && kameraX-locationsX[i]<(cubeSize/2+0.05) && kameraY-locationsY[i]<(cubeSize/2+0.05) && kameraY-locationsY[i]>-(cubeSize/2+0.05) && kameraZ-locationsZ[i]<cubeSize && kameraZ-locationsZ[i]>0)
			{
				kameraZ=locationsZ[i]+cubeSize+0.01; 
				lookZ=lookZ+gravityValue;
				gravityBoolean=0;
			}
		}
		//cheese collision
		//else if(building[i]==10)
		//{
		//	if(kameraX-locationsX[i]>-(cubeSize/4+0.05) && kameraX-locationsX[i]<(cubeSize/4+0.05) && kameraY-locationsY[i]<(cubeSize/4+0.05) && kameraY-locationsY[i]>-(cubeSize/4+0.05) && kameraZ-locationsZ[i]<cubeSize/2 && kameraZ-locationsZ[i]>0.1)
		//	{
		//		cheesesLeft=cheesesLeft-1;
		//		building[i]=0;
		//		if(cheesesLeft==0) stageCleared=1;
		//	}
		//}
		//collision for black holes
		//else if(building[i]==3)
		//{
		//	if(kameraX-locationsX[i]>-(cubeSize/2+0.25) && kameraX-locationsX[i]<(cubeSize/2+0.25) && kameraY-locationsY[i]<(cubeSize/2+0.25) && kameraY-locationsY[i]>-(cubeSize/2+0.25) && kameraZ-locationsZ[i]<cubeSize && kameraZ-locationsZ[i]>-cubeSize/2)
		//	{
		//		kameraZ=locationsZ[i]+cubeSize+0.01; 
		//		gravityBoolean=0;
		//	}
		//}
	}
	
	//Next location of Z and state
	if(spaceKeyIsPressed==1)
	{
		kameraZ=kameraZ+gravityValue;
		lookZ=lookZ+gravityValue;
		if(gravityBoolean>=cubeSize+cubeSize/4) spaceKeyIsPressed=0;
	}
	if(spaceKeyIsPressed==0)
	{
		kameraZ=kameraZ-gravityValue;
		lookZ=lookZ-gravityValue;
	}
	
}
// IF YOU PRESS DOWN A KEY FROM KEYBOARD, MAKE THE FLAG=1
document.addEventListener('keydown', function(event) {
    if(event.keyCode == 65) {	//65 is the keyCode for A kai a -->left
       leftKeyIsPressed=1;
    }
    else if(event.keyCode == 68) { //68 is the keyCode for D kai d -->right
        rightKeyIsPressed=1;
    }
	else if(event.keyCode == 87) { //87 is the keyCode for W kai w
		upKeyIsPressed=1;
	}
	else if(event.keyCode == 83) { // 83 is the keyCode for S kai s
		downKeyIsPressed=1;
		}
	//Listen to space key only when the player stand on the ground or upon an item
	else if(event.keyCode == 32) { //32 is the keyCode for space
		//prevent the default listener
		event.preventDefault();
		if(gravityBoolean==0) spaceKeyIsPressed=1;
	}
});
//IF YOU LET GO OF KEY OF KEYBOARD, MAKE THE FLAG=0
document.addEventListener('keyup', function(event) {
	if(event.keyCode == 65) { //65 is the keyCode for A kai a -->left
		leftKeyIsPressed=0;
	}
	else if(event.keyCode == 68) { //68 is the keyCode for D kai d -->right
		rightKeyIsPressed=0;
	}
	else if(event.keyCode == 87) { //87 is the keyCode for W kai w
		upKeyIsPressed=0;
	}
	else if(event.keyCode == 83) { // 83 is the keyCode for S kai s
		downKeyIsPressed=0;
	}
	else if(event.keyCode == 32) { //32 is the keyCode for space
		//prevent the default listener
		event.preventDefault();
		spaceKeyIsPressed=0;
	}
});

//EVENT FOR MOUSE! (EPITELOYS TO PROS8ETW!!)

	document.onmousedown=function(event)
	{
		event.stopPropagation();
		
		mouseDown = mouseDown+1;
		lastMouseX = event.clientX;
		lastMouseZ = event.clientY;	
	};
	
	document.onmouseup=function(event)
	{
		event.stopPropagation();
		if(mouseDown==2) mouseDown=1;
		else mouseDown = 0;
	};

	// EVENT FOR MOUSE MOVEMENT
	document.onmousemove=function(event) 
	{
		if (mouseDown==1 || mouseDown==2) 
		{
			var newMouseX = event.clientX;
			var newMouseZ = event.clientY;
		
			var totalMovementX = 0.15*(newMouseX - lastMouseX);
			var totalMovementZ = 0.03*(newMouseZ - lastMouseZ);
			lookX=(lookX-totalMovementX*Math.PI/180.0)%360.0;
			
			//Don't let lookZ to become infinite
			if(lookZ<=worldSize/2) { lookZ=lookZ-totalMovementZ;}
			else lookZ=worldSize/2;
			
			//Don't let lookZ to go below Zero
			if(lookZ>-5) { lookZ=lookZ-totalMovementZ;}
			else lookZ=-5;
		
			lastMouseX = newMouseX
			lastMouseZ = newMouseZ;
			
		}
	}

// END OF EVENT HANDLERS

function startAnim()
{
	if (!requestId)
		anim();
}

function anim()
{
	requestId=window.requestAnimationFrame(anim);
	redraw();
}



//change the texture used for skybox and floor
function changeCurrentWallTexture(choosenWall)
{
	currentWall=choosenWall;
	if(choosenWall==1)
	{
		document.getElementsByName("stag")[0].style.background= "url('/cubeGame/stag1.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("stag")[1].style.background=	" url('/cubeGame/stoneButton.jpg') no-repeat scroll 0 0 transparent";
		document.getElementsByName("stag")[2].style.background= " url('/cubeGame/woodButton.jpg') no-repeat scroll 0 0 transparent";
		document.getElementsByName("stag")[3].style.background= " url('/cubeGame/lavaButton.jpg') no-repeat scroll 0 0 transparent";
	}                                              
	else if(choosenWall==2)
	{
		document.getElementsByName("stag")[0].style.background= " url('/cubeGame/plainsButton.jpg') no-repeat scroll 0 0 transparent";
		document.getElementsByName("stag")[1].style.background=	" url('/cubeGame/stag2.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("stag")[2].style.background= " url('/cubeGame/woodButton.jpg') no-repeat scroll 0 0 transparent";
		document.getElementsByName("stag")[3].style.background= " url('/cubeGame/lavaButton.jpg') no-repeat scroll 0 0 transparent";
	}
	else if(choosenWall==3)
	{
		document.getElementsByName("stag")[0].style.background= " url('/cubeGame/plainsButton.jpg') no-repeat scroll 0 0 transparent";
		document.getElementsByName("stag")[1].style.background= "url('/cubeGame/stoneButton.jpg') no-repeat scroll 0 0 transparent";
		document.getElementsByName("stag")[2].style.background= " url('/cubeGame/stag3.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("stag")[3].style.background= " url('/cubeGame/lavaButton.jpg') no-repeat scroll 0 0 transparent";
	}
	else
	{
		document.getElementsByName("stag")[0].style.background= " url('/cubeGame/plainsButton.jpg') no-repeat scroll 0 0 transparent";
		document.getElementsByName("stag")[1].style.background= " url('/cubeGame/stoneButton.jpg') no-repeat scroll 0 0 transparent";
		document.getElementsByName("stag")[2].style.background= " url('/cubeGame/woodButton.jpg') no-repeat scroll 0 0 transparent";
		document.getElementsByName("stag")[3].style.background= " url('/cubeGame/stag4.png') no-repeat scroll 0 0 transparent";
	}
}
function changeCurrentBuilding(choosenBuilding)
{
	currentBuilding=choosenBuilding;
	if(choosenBuilding==0)
	{
		document.getElementsByName("buil")[0].style.background= "url('/cubeGame/buil1.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[1].style.background=	" url('/cubeGame/stoneButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[2].style.background= " url('/cubeGame/woodButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[4].style.background= " url('/cubeGame/lavaButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[4].style.background= " url('/cubeGame/cheese.png') no-repeat scroll 0 0 transparent";
	}                                              
	else if(choosenBuilding==1)
	{
		document.getElementsByName("buil")[0].style.background= " url('/cubeGame/emptyButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[1].style.background=	" url('/cubeGame/buil2.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[2].style.background= " url('/cubeGame/woodButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[3].style.background= " url('/cubeGame/lavaButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[4].style.background= " url('/cubeGame/cheese.png') no-repeat scroll 0 0 transparent";
	}
	else if(choosenBuilding==2)
	{
		document.getElementsByName("buil")[0].style.background= " url('/cubeGame/emptyButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[1].style.background= "url('/cubeGame/stoneButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[2].style.background= " url('/cubeGame/buil3.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[3].style.background= " url('/cubeGame/lavaButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[4].style.background= " url('/cubeGame/cheese.png') no-repeat scroll 0 0 transparent";
	}
	else if(choosenBuilding==3)
	{
		document.getElementsByName("buil")[0].style.background= " url('/cubeGame/emptyButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[1].style.background= " url('/cubeGame/stoneButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[2].style.background= " url('/cubeGame/woodButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[3].style.background= " url('/cubeGame/buil4.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[4].style.background= " url('/cubeGame/cheese.png') no-repeat scroll 0 0 transparent";
	}
	else
	{
		document.getElementsByName("buil")[0].style.background= " url('/cubeGame/emptyButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[1].style.background= " url('/cubeGame/stoneButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[2].style.background= " url('/cubeGame/woodButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[3].style.background= " url('/cubeGame/lavaButton.png') no-repeat scroll 0 0 transparent";
		document.getElementsByName("buil")[4].style.background= " url('/cubeGame/buil5.png') no-repeat scroll 0 0 transparent";
	}
}

</script>















<title> Ledriel's Gaming </title> </head>
<link rel="stylesheet" href="../My_Css.css"> 
<?php
/////////////////////////////////////////////////////////GUI///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//MANAGE SESSIONS: an einai logarismenos kapios xrhsths
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1500)) 
{
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}

//CHOOSE BACKGROUND FOR USER AND FOR ADMIN
if (isset($_SESSION['status']) && $_SESSION['status']=="admin")  echo "<body background=\"../background2.jpg\" bgcolor=\"ALICEBLUE\" onload=\"startup()\" oncontextmenu=\"return false;\">";
else echo "<body background=\"../background.jpg\" bgcolor=\"ALICEBLUE\" onload=\"startup()\" oncontextmenu=\"return false;\">";

?>
<My_Title>Ledriel's Gaming </My_Title>
<button_Image><img src="../button.png" style="height:4em; width:50em;">  </button_Image>
<button_Pos>
<div class="background">
   <button id="button" ONCLICK="window.location.href='/home.php'" onmouseover="" style="cursor: pointer;">Home Page</button>
   &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
   <button id="button" ONCLICK="window.location.href='/games.php'" onmouseover="" style="cursor: pointer;">Games</button>
   &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
   <button id="button" ONCLICK="window.location.href='/aboutus.php'" onmouseover="" style="cursor: pointer;">About Me</button>
</div>
</button_Pos>

<?php


//sindesou me to phpmyadmin
$con = mysqli_connect('localhost','root') or
die('Could not connect to the database');
//sindesou me to database store_database
mysqli_select_db($con,'ptuxiaki');

//add new note
if(isset($_POST['newNote']))
{
//see your timezone
//$timezone = date_default_timezone_get();
//echo "The current server timezone is: " . $timezone;

	date_default_timezone_set('Europe/Athens');
	$date = date('Y/m/d h:i:s', time());
	$parNews=$_POST['news'];
	$sql="INSERT INTO news (note,day) VALUES ('$parNews','$date')";
    mysqli_query($con,$sql);
}
// delete a note
else if(isset($_POST['deleteNote']))
{
	$noteI=$_POST['noteId'];
	$sql="DELETE FROM news WHERE noteID=$noteI";
    mysqli_query($con,$sql);
}


//login-logout-register
if(isset($_POST['login']))
{
	$result = mysqli_query($con,"SELECT status FROM users WHERE username='".$_POST['userText']."' AND password='".$_POST['passText']."'");
	$row = mysqli_fetch_row($result);
	$n = mysqli_num_rows($result);
	if($n==1) 
	{
		if($row[0]=="active" or $row[0]=="unbanned" or $row[0]=="admin")
		{
			$_SESSION['user']=$_POST['userText'];
			$_SESSION['status']=$row[0];
			$_SESSION['LAST_ACTIVITY'] = time();
			//setcookie("user", $_POST['userText'], time()+400);
			header("Location: /home.php");
		}
		else if($row[0]=="banned")
		{
			header("Location: /home.php?loginStatus=BANNED");
		}
		else
		{
			header("Location: /home.php?loginStatus=INNACTIVE");
		}
	}
	else header("Location: /home.php?loginStatus=BAD_COMBINATION");
}
else if(isset($_POST['register']))
{
	header("Location: /register.php");
}
else if(isset($_POST['logout']))
{
	//setcookie("user", "", time()-1);
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
	
	header("Location: /home.php");
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




//first Echo
?><white_bold2><My_TopRight><?php 
if (isset($_SESSION['user']))
{
	//an o logarismenos einai o admin tote
	if (isset($_SESSION['status']) && $_SESSION['status']=="admin") 
	{
		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
		$display_block = "<green>Welcome   " .$_SESSION['user'];
		//$display_block = "Welcome   " .$_COOKIE["user"];
		$display_block .= "<form method=\"POST\" action=\"/home.php\">";
		$display_block .= "<br>";
		$display_block .= "<input type=\"Submit\" name=\"logout\" value=\"Log out\"/>";
		$display_block .= "<input type=button onClick=\"location.href='/cubeGame/myMaps.php'\" value='My Maps'>";
		$display_block .= "</Form>";
		$display_block .= "<form method=\"POST\" action=\"/userSetup.php\">";
		$display_block .= "<br>";
		$display_block .= "<input type=\"Submit\" name=\"userSetup\" value=\"Users Setup\"/>";
		$display_block .= "</Form></green>";
	}
	else
	{
		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
		$display_block = "Welcome   " .$_SESSION['user'];
		//$display_block = "Welcome   " .$_COOKIE["user"];
		$display_block .= "<form method=\"POST\" action=\"/home.php\">";
		$display_block .= "<br>";
		$display_block .= "<input type=\"Submit\" name=\"logout\" value=\"Log out\"/>";
		$display_block .= "<input type=button onClick=\"location.href='/cubeGame/myMaps.php'\" value='My Maps'>";
		$display_block .= "</Form>";
	}
}
//an den einai logarismenos kapios xrhsths
else 
{
	?><fieldset>  <legend>Log In </legend><?php
	//an prospa8hse na kanei login kai apetuxe
	if(isset($_GET['loginStatus']))
	{
		$display_block = "<br><b> LOGIN FAILED</b><br>";
		$display_block .= "Anonymous";
		if($_GET['loginStatus']=="INNACTIVE") echo "<script type='text/javascript'>alert('YOUR ACCOUNT IS STILL INNACTIVE');</script>";
		if($_GET['loginStatus']=="BANNED") echo "<script type='text/javascript'>alert('YOUR ACCOUNT HAD BEEN BANNED');</script>"; 
	}
	else if(isset($_GET["New_Account"]))
	{
		$display_block = "Your account had been created";
	}
	//den exei prospa8hsei na kanei akoma login
	else $display_block = "Anonymous";
	
	//an dn einai kaneis logarismenos tote na mporei na kanei o anonymos user logIn
	$display_block .= "<form method=\"POST\" action=\"/home.php\">";
	$display_block .= "<br>";
	$display_block .= "<b>username: <input type=\"text\" size=16 name=\"userText\">";
	$display_block .= "<br>";
	$display_block .= "password: <input type=\"password\" size=16 name=\"passText\"> </b>";
	$display_block .= "<br>";
	$display_block .= "<input type=\"Submit\" name=\"login\" value=\"Log In\"/>";
	$display_block .= "<input type=\"Submit\" name=\"register\" value=\"Register\">";
	$display_block .= "</Form>";
}

echo "$display_block <br>";
?></fieldset></My_TopRight></white_bold2><?php
//2o echo
?><C><h3><white_bold2><green><b>CUBE GAME</b></green></white_bold2></h3> </C>
<StartCreation>

 </StartCreation>

 
 
<script>
//SEND TO DATABASE!!
$(document).ready(function() {
	$('#SendToDatabase').on('click',function() 
	{{
		if(cheesesLeft>0)
		{
			//arr is the objects and all the others are the stage's Details
			//var arr = ["ww", "ww", "asd@asd.com",1,"11",1,"wq","banned"];
			
			var dataObject = { stageName: '<?=$_POST['stageName']?>',
								Author: '<?=$_SESSION['user']?>',
								stageLevel : currentWall,
								UserX : kameraX,
								UserY : kameraY,
								UserZ : kameraZ,
								numberOfCheeses : cheesesLeft,
								ii: ii,
								building: building,
								locX: locationsX,
								locY: locationsY,
								locZ: locationsZ};
		
			$.ajax({type: "POST",
				url: "insertStageToDatabase.php",
				data: dataObject,
				cache: false
				});
				
			//wait some seconds to redict on myMaps
			setTimeout(
				function() 
				{
					window.location.href='/cubeGame/myMaps.php';
				}, 100);
		}
		else
		{
			alert("!!You forgot about the cheeses!!");
		}
	}});
	});
</script>

 
 
 
<GameCanvas2>
	<img src="greenBox.png" style="height:60em; width:80em;">
</GameCanvas2>
<StagesLoc>
	<img src="Stages.png" style="height:4em; width:8em;"> <br><br>
	<br>&nbsp&nbsp <button name="stag" onClick=changeCurrentWallTexture(1) style="height: 10em; width: 10em; background: url('/cubeGame/stag1.png') no-repeat scroll 0 0 transparent; border: none;"></button>
	<br>&nbsp&nbsp <button name="stag" onClick=changeCurrentWallTexture(2) style="height: 10em; width: 10em; background: url('/cubeGame/stoneButton.jpg') no-repeat scroll 0 0 transparent; border: none;"></button>
	<br>&nbsp&nbsp <button name="stag" onClick=changeCurrentWallTexture(3) style="height: 10em; width: 10em; background: url('/cubeGame/woodButton.jpg') no-repeat scroll 0 0 transparent; border: none;"></button>
	<br>&nbsp&nbsp <button name="stag" onClick=changeCurrentWallTexture(4) style="height: 10em; width: 10em; background: url('/cubeGame/lavaButton.jpg') no-repeat scroll 0 0 transparent; border: none;"></button>
</StagesLoc>
<BuildingsLoc>
	<img src="Buildings.png" style="height:3em; width:8em;">
	<button name="buil" onClick=changeCurrentBuilding(0) style="height: 10em; width: 10em; background: url('/cubeGame/emptyButton.png') no-repeat scroll 0 0 transparent; border: none;"></button>
	<button name="buil" onClick=changeCurrentBuilding(1) style="height: 10em; width: 10em; background: url('/cubeGame/buil2.png') no-repeat scroll 0 0 transparent; border: none;"></button>
	<button name="buil" onClick=changeCurrentBuilding(2) style="height: 10em; width: 10em; background: url('/cubeGame/woodButton.png') no-repeat scroll 0 0 transparent; border: none;"></button>
	<button name="buil" onClick=changeCurrentBuilding(3) style="height: 10em; width: 10em; background: url('/cubeGame/lavaButton.png') no-repeat scroll 0 0 transparent; border: none;"></button>
	<button name="buil" onClick=changeCurrentBuilding(10) style="height: 10em; width: 10em; background: url('/cubeGame/cheese.png') no-repeat scroll 0 0 transparent; border: none;"></button>
	<BR> 
</BuildingsLoc>
 
 <GameCanvas>
</body>
<body onload="startup()" oncontextmenu="return false;" bgcolor=cyane>
<canvas id="myGLCanvas" >
</canvas>
<br>
</GameCanvas>

<GameCanvas3>
<button onClick=setNewBuilding(currentBuilding) style="height: 4em; width: 23em; background: url('/cubeGame/buildNOW.png') no-repeat scroll 0 0 transparent; border: none;"></button>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<button type=Submit id="SendToDatabase" value="" onmouseover="" style="cursor: pointer; color:green; height: 4em; width: 30em; background: url('/cubeGame/save.png') no-repeat scroll 0 0 transparent; border: none;"></button>
<br>
</GameCanvas3>

</p>
</body>

</html>

