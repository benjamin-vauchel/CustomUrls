CustomUrls.grid.CustomUrls = function(config) {
    config = config || {};
    var cb = new Ext.ux.grid.CheckColumn({
        header: _('customurls.active')
        ,dataIndex: 'active'
        ,width: 40
        ,sortable: true
        ,onMouseDown: this.saveCheckbox
    });
    Ext.applyIf(config,{
        id: 'customurls-grid-customurls'
        ,url: CustomUrls.config.connectorUrl
        ,baseParams: { action: 'mgr/customurls/getList' }
        ,save_action: 'mgr/customurls/updateFromGrid'
        ,fields: ['id','pattern','uri','criteria_key','criteria_value','usergroup','override','active']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'pattern'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,editable: true
            ,width: 20
        },{
            header: _('customurls.pattern')
            ,dataIndex: 'pattern'
            ,sortable: true
            ,editable: true
            ,editor: { xtype: 'textfield' }
        },{
            header: _('customurls.criteria_key')
            ,dataIndex: 'criteria_key'
            ,sortable: true
            ,editable: true
            ,width: 50
            ,editor: { xtype: 'textfield' }
        },{
            header: _('customurls.criteria_value')
            ,dataIndex: 'criteria_value'
            ,sortable: true
            ,editable: true
            ,width: 50
            ,editor: { xtype: 'textfield' }
        },{
            header: _('customurls.usergroup')
            ,dataIndex: 'usergroup'
            ,sortable: true
            ,editable: true
            ,width: 50
            ,editor: { xtype: 'modx-combo-usergroup' ,renderer: true }
        },{
            header: _('customurls.uri')
            ,dataIndex: 'uri'
            ,sortable: true
            ,editable: true
            ,width: 50
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
        },{
            header: _('customurls.override')
            ,dataIndex: 'override'
            ,sortable: true
            ,editable: true
            ,width: 50
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
        },{
            header: _('customurls.active')
            ,dataIndex: 'active'
            ,sortable: true
            ,editable: true
            ,width: 50
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
        }]
        ,tbar:[{
            text: _('customurls.customurl_create')
            ,handler: { xtype: 'customurls-window-customurl-create' ,blankValues: true }
        },'->',{
            text: _('customurls.customurl_generate')
            ,handler: this.generateCustomUrls
        }]
    });
    CustomUrls.grid.CustomUrls.superclass.constructor.call(this,config)
};
Ext.extend(CustomUrls.grid.CustomUrls,MODx.grid.Grid,{
    getMenu: function() {
        return [{
            text: _('customurls.customurl_update')
            ,handler: this.updateCustomUrl
        },'-',{
            text: _('customurls.customurl_remove')
            ,handler: this.removeCustomUrl
        }];
    }
    ,updateCustomUrl: function(btn,e) {
        if (!this.updateCustomUrlWindow) {
            this.updateCustomUrlWindow = MODx.load({
                xtype: 'customurls-window-customurl-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        }
        this.updateCustomUrlWindow.setValues(this.menu.record);
        this.updateCustomUrlWindow.show(e.target);
    }
    ,removeCustomUrl: function() {
        MODx.msg.confirm({
            title: _('customurls.customurl_remove')
            ,text: _('customurls.customurl_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/customurl/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        })
    }
    ,generateCustomUrls: function() {
        MODx.msg.confirm({
            title: _('customurls.customurl_generate')
            ,text: _('customurls.customurl_generate_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/customurls/generate'
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        })
    }

});

Ext.reg('customurls-grid-customurls',CustomUrls.grid.CustomUrls);

CustomUrls.window.UpdateCustomUrl = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('customurls.customurl_update')
        ,url: CustomUrls.config.connectorUrl
        ,baseParams: {
            action: 'mgr/customurl/update'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('customurls.pattern')
            ,name: 'pattern'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('customurls.criteria_key')
            ,name: 'criteria_key'
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('customurls.criteria_value')
            ,name: 'criteria_value'
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-usergroup'
            ,fieldLabel: _('customurls.usergroup')
            ,name: 'usergroup'
            ,hiddenName: 'usergroup'
            ,anchor: '50%'
            ,editable: true
            ,triggerAction: 'all'
        },{
            xtype: 'combo-boolean'
            ,fieldLabel: _('customurls.uri')
            ,name: 'uri'
            ,hiddenName: 'uri'
            ,anchor: '50%'
        },{
            xtype: 'combo-boolean'
            ,fieldLabel: _('customurls.override')
            ,name: 'override'
            ,hiddenName: 'override'
            ,anchor: '50%'
        },{
            xtype: 'combo-boolean'
            ,fieldLabel: _('customurls.active')
            ,name: 'active'
            ,hiddenName: 'active'
            ,anchor: '50%'
        }]
    });
    CustomUrls.window.UpdateCustomUrl.superclass.constructor.call(this,config);
};
Ext.extend(CustomUrls.window.UpdateCustomUrl,MODx.Window);
Ext.reg('customurls-window-customurl-update',CustomUrls.window.UpdateCustomUrl);

CustomUrls.window.CreateCustomUrl = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('customurls.customurl_create')
        ,url: CustomUrls.config.connectorUrl
        ,baseParams: {
            action: 'mgr/customurl/create'
        }
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('customurls.pattern')
            ,name: 'pattern'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('customurls.criteria_key')
            ,name: 'criteria_key'
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('customurls.criteria_value')
            ,name: 'criteria_value'
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-usergroup'
            ,fieldLabel: _('customurls.usergroup')
            ,name: 'usergroup'
            ,hiddenName: 'usergroup'
            ,anchor: '50%'
            ,editable: true
            ,triggerAction: 'all'
        },{
            xtype: 'combo-boolean'
            ,fieldLabel: _('customurls.uri')
            ,name: 'uri'
            ,hiddenName: 'uri'
            ,anchor: '50%'
        },{
            xtype: 'combo-boolean'
            ,fieldLabel: _('customurls.override')
            ,name: 'override'
            ,hiddenName: 'override'
            ,anchor: '50%'
        },{
            xtype: 'combo-boolean'
            ,fieldLabel: _('customurls.active')
            ,name: 'active'
            ,hiddenName: 'active'
            ,anchor: '50%'
            ,value: true
        }]
    });
    CustomUrls.window.CreateCustomUrl.superclass.constructor.call(this,config);
};
Ext.extend(CustomUrls.window.CreateCustomUrl,MODx.Window);
Ext.reg('customurls-window-customurl-create',CustomUrls.window.CreateCustomUrl);
