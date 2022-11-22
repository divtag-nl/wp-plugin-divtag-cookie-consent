/**
 * @param {String} colorCode 
 * Hex color, for example '#ff9900'
 * 
 * @param {Number} shadeLevel
 * Between -100 and 100 (negative for a darker shade, positive for a lighter shade)
 */

export default function colorShade(colorCode, shadeLevel) {
  var R = parseInt(colorCode.substring(1,3),16);
  var G = parseInt(colorCode.substring(3,5),16);
  var B = parseInt(colorCode.substring(5,7),16);

  R = parseInt(R * (100 + shadeLevel) / 100);
  G = parseInt(G * (100 + shadeLevel) / 100);
  B = parseInt(B * (100 + shadeLevel) / 100);

  R = (R<255)?R:255;  
  G = (G<255)?G:255;  
  B = (B<255)?B:255;  

  var RR = ((R.toString(16).length==1)?"0"+R.toString(16):R.toString(16));
  var GG = ((G.toString(16).length==1)?"0"+G.toString(16):G.toString(16));
  var BB = ((B.toString(16).length==1)?"0"+B.toString(16):B.toString(16));

  return "#"+RR+GG+BB;
}