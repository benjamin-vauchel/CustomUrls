Ext.onReady(function() {
    MODx.load({ xtype: 'customurls-page-home'});
});
 
CustomUrls.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'customurls-panel-home'
            ,renderTo: 'customurls-panel-home-div'
        }]
    });
    CustomUrls.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(CustomUrls.page.Home,MODx.Component);
Ext.reg('customurls-page-home',CustomUrls.page.Home);
