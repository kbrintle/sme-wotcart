/*
  Highcharts JS v6.1.0 (2018-04-13)

 Indicator series type for Highstock

 (c) 2010-2017 Sebastian Bochan

 License: www.highcharts.com/license
*/
(function(n){"object"===typeof module&&module.exports?module.exports=n:n(Highcharts)})(function(n){(function(b){function n(d,b,f,h,a,l,c,g){d=0>c?f[h-1]:f[h-1][c];return[b[h-1],void 0===l?g:d*a+l*(1-a)]}var q=b.isArray;b=b.seriesType;b("ema","sma",{params:{index:0,period:14}},{getValues:function(d,b){var f=b.period,h=d.xData,a=(d=d.yData)?d.length:0,l=2/(f+1),c=0,g=0,u=[],r=[],k=[],e=-1,t=[],m;if(h.length<f)return!1;for(q(d[0])&&(e=b.index?b.index:0);c<f;)t.push([h[c],0>e?d[c]:d[c][e]]),g+=0>e?d[c]:
d[c][e],c++;b=g/f;for(f=c;f<a;f++)m=n(t,h,d,f,l,m,e,b),u.push(m),r.push(m[0]),k.push(m[1]),m=m[1],t.push([h[f],0>e?d[f]:d[f][e]]);m=n(t,h,d,f,l,m,e);u.push(m);r.push(m[0]);k.push(m[1]);return{values:u,xData:r,yData:k}}})})(n);(function(b){var n=b.seriesType,q=b.each,d=b.merge,p=b.defined,f=b.seriesTypes.sma,h=b.seriesTypes.ema;n("macd","sma",{params:{shortPeriod:12,longPeriod:26,signalPeriod:9,period:26},signalLine:{zones:[],styles:{lineWidth:1,lineColor:void 0}},macdLine:{zones:[],styles:{lineWidth:1,
lineColor:void 0}},threshold:0,groupPadding:.1,pointPadding:.1,states:{hover:{halo:{size:0}}},tooltip:{pointFormat:'\x3cspan style\x3d"color:{point.color}"\x3e\u25cf\x3c/span\x3e \x3cb\x3e {series.name}\x3c/b\x3e\x3cbr/\x3eValue: {point.MACD}\x3cbr/\x3eSignal: {point.signal}\x3cbr/\x3eHistogram: {point.y}\x3cbr/\x3e'},dataGrouping:{approximation:"averages"},minPointLength:0},{nameComponents:["longPeriod","shortPeriod","signalPeriod"],pointArrayMap:["y","signal","MACD"],parallelArrays:["x","y","signal",
"MACD"],pointValKey:"y",markerAttribs:b.noop,getColumnMetrics:b.seriesTypes.column.prototype.getColumnMetrics,crispCol:b.seriesTypes.column.prototype.crispCol,init:function(){f.prototype.init.apply(this,arguments);this.options=d({signalLine:{styles:{lineColor:this.color}},macdLine:{styles:{color:this.color}}},this.options);this.macdZones={zones:this.options.macdLine.zones,startIndex:0};this.signalZones={zones:this.macdZones.zones.concat(this.options.signalLine.zones),startIndex:this.macdZones.zones.length};
this.resetZones=!0},toYData:function(a){return[a.y,a.signal,a.MACD]},translate:function(){var a=this,l=["plotSignal","plotMACD"];b.seriesTypes.column.prototype.translate.apply(a);q(a.points,function(c){q([c.signal,c.MACD],function(g,b){null!==g&&(c[l[b]]=a.yAxis.toPixels(g,!0))})})},destroy:function(){this.graph=null;this.graphmacd=this.graphmacd.destroy();this.graphsignal=this.graphsignal.destroy();f.prototype.destroy.apply(this,arguments)},drawPoints:b.seriesTypes.column.prototype.drawPoints,drawGraph:function(){for(var a=
this,l=a.points,c=l.length,b=a.options,h=a.zones,r={options:{gapSize:b.gapSize}},k=[[],[]],e;c--;)e=l[c],p(e.plotMACD)&&k[0].push({plotX:e.plotX,plotY:e.plotMACD,isNull:!p(e.plotMACD)}),p(e.plotSignal)&&k[1].push({plotX:e.plotX,plotY:e.plotSignal,isNull:!p(e.plotMACD)});q(["macd","signal"],function(c,l){a.points=k[l];a.options=d(b[c+"Line"].styles,r);a.graph=a["graph"+c];a.currentLineZone=c+"Zones";a.zones=a[a.currentLineZone].zones;f.prototype.drawGraph.call(a);a["graph"+c]=a.graph});a.points=l;
a.options=b;a.zones=h;a.currentLineZone=null},getZonesGraphs:function(a){var b=f.prototype.getZonesGraphs.call(this,a),c=b;this.currentLineZone&&(c=b.splice(this[this.currentLineZone].startIndex+1),c.length?c.splice(0,0,a[0]):c=[a[0]]);return c},applyZones:function(){var a=this.zones;this.zones=this.signalZones.zones;f.prototype.applyZones.call(this);this.options.macdLine.zones.length&&this.graphmacd.hide();this.zones=a},getValues:function(a,b){var c=0,g=[],f=[],d=[],k,e;k=h.prototype.getValues(a,
{period:b.shortPeriod});e=h.prototype.getValues(a,{period:b.longPeriod});k=k.values;e=e.values;for(a=1;a<=k.length;a++)p(e[a-1])&&p(e[a-1][1])&&g.push([k[a+b.shortPeriod+1][0],0,null,k[a+b.shortPeriod+1][1]-e[a-1][1]]);for(a=0;a<g.length;a++)f.push(g[a][0]),d.push([0,null,g[a][3]]);b=h.prototype.getValues({xData:f,yData:d},{period:b.signalPeriod,index:2});b=b.values;for(a=0;a<g.length;a++)g[a][0]>=b[0][0]&&(g[a][2]=b[c][1],d[a]=[0,b[c][1],g[a][3]],null===g[a][3]?(g[a][1]=0,d[a][0]=0):(g[a][1]=g[a][3]-
b[c][1],d[a][0]=g[a][3]-b[c][1]),c++);return{values:g,xData:f,yData:d}}})})(n)});
