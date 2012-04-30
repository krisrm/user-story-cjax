CrawlDirector = {
		restore : "",
		crawlspec : {}
};

Crawler = {
	crawlspec : {clickable:[]},
	hasDirections: false,
	called : []
};
Assert = {
	assertStack : new Array(),
	assertCalled : new Array(),
	asserts : 0
};



Crawler.start=function(callId){
	if (Crawler.hasDirections){
		return;
	}
	for (var i = 0; i < Crawler.called.length; i++){
		if (Crawler.called[i] == callId){
			crawlSpec={};
			return;
		}
	}
	Crawler.hasDirections = true;
	Crawler.called.push(callId);
};
Crawler.buildRestore = function(){
	var r ="Crawler.called=eval("+JSON.stringify(Crawler.called)+");";
	r+= "Assert.asserts="+Assert.asserts+";";
	r+= "Assert.assertStack=eval("+JSON.stringify(Assert.assertStack)+");";
	r+= "Assert.assertCalled=eval("+JSON.stringify(Assert.assertCalled)+");";
	return r;
};

Crawler.go = function(url){
	this.goAbs(this.APP+url);
};
Crawler.goAbs = function(url){
	if (Crawler.hasDirections){
		return;
	}
	this.crawlspec.url=url;
};

Crawler.click = function(element){
	if (Crawler.hasDirections){
		return;
	}
	//TODO flesh out
	Crawler.crawlspec.clickable.push({"tagName":element.tagName ,"id":element.id});
};

Crawler.getReturn = function(){
	if (this.hasDirections){
		CrawlDirector.restore = this.buildRestore();
		CrawlDirector.crawlspec = this.crawlspec;
		return "D " + JSON.stringify(CrawlDirector);
	} else {
		return "A " + JSON.stringify(Assert);
	}
};

Assert.assertTrue = function(id, value, error){
	if (Crawler.hasDirections){return;}
	for (var i = 0; i < this.assertCalled.length; i++){
		if (this.assertCalled[i] == id){
			return;
		}
	}
	this.asserts++;
	this.assertCalled.push(id);
	if (value !== true){
		this.assertStack.push({"id":id,"error":error});
	}
};
Assert.assertFalse = function(id, value, error){
	Assert.assertTrue(id,!value,error);
};
Assert.assertNotNull = function(id, value, error){
	Assert.assertTrue(id,value!=null,error);
};
Assert.assertNull = function(id, value, error){
	Assert.assertTrue(id,value==null,error);
};
Assert.assertEquals = function(id, value1,value2, error){
	Assert.assertTrue(id,value1==value2,error);
};

Assert.debug = function(){
	for (var i = 0; i < this.assertStack.length; i++){
		var x = this.assertStack[i];
		alert(x.id + "-" + x.error);
	}
};

function eid(id){
	return document.getElementById(id);
}
