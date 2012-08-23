var CustomUrls = function(config) {
    config = config || {};
	CustomUrls.superclass.constructor.call(this,config);
};
Ext.extend(CustomUrls,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
Ext.reg('customurls',CustomUrls);
CustomUrls = new CustomUrls();