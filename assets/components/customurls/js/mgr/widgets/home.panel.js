CustomUrls.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+_('customurls.management')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,items: [{
                title: _('customurls')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('customurls.management_desc')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'customurls-grid-customurls'
                    ,cls: 'main-wrapper'
                    ,preventRender: true
                }]
            }]
        }]
    });
    CustomUrls.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(CustomUrls.panel.Home,MODx.Panel);
Ext.reg('customurls-panel-home',CustomUrls.panel.Home);
