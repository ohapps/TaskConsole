Ext.BLANK_IMAGE_URL = '/scripts/ext-3.0.0/resources/images/default/s.gif' ;  
//Ext.form.Field.prototype.msgTarget = "side";
Ext.QuickTips.init();    
Ext.data.Connection.prototype.disableCaching = false;
Ext.MessageBox.minWidth = 400;
//Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

var ynss = new Ext.data.SimpleStore( { id: 0, fields: ["key", "val"], data : [ ["1","yes"], ["0","no"] ] } ) ;