Ext.namespace("Ext.ux");    

Ext.ux.comboBoxRenderer = function(combo) {
  return function(value) {
  	try{
    	//var idx = combo.store.find(combo.valueField, value );
    	var rec = combo.store.getById(value);
    	return rec.get(combo.displayField);
  	}catch(err){
  		return value;
  	}
  };
}

        
Ext.ux.phoneRenderer = function( value ) {
   	if ( value.length == 10 ) return String.format( '({0}) {1}-{2}', value.substr(0,3), value.substr(3,3), value.substr(6,4) ) ;
    else return value ;
}

Ext.ux.moneyRenderer = function ( value ){
	if ( value != "" ){
		return Ext.util.Format.usMoney( value ); 
	}else{
		return value;
	}	
}


Ext.ux.EasyEditorGridPanel = function(config){
    
        Ext.ux.EasyEditorGridPanel.superclass.constructor.call(this, config);

}
 
Ext.extend( Ext.ux.EasyEditorGridPanel, Ext.grid.EditorGridPanel, {
                     
        dateFields  	: [],
        dateFormat  	: "",
        pkCol 			: "",
        defaultEditCol	: 1,        
        params			: [],
        extraParams 	: "",        			
        noRowsSelectMsg	: "",
        rec				: [],
        reloadOnChange 	: false,
        reloadParams	: [],         
        url				: "",
        updatePK		: false,
        maskSave		: false,
        postParam		: "",
        callback		: "",
        defaults		: { 
        					params: [], 
        					extraParams: "", 
        					url : "", 
        					dateFields: [], 
        					pkCol : "ID", 
        					dateFormat : "d-M-y", 
        					reloadOnChange : false, 
        					noRowsSelectMsg : "Please select a row",         					
        					defaultEditCol : 1,
        					updatePK : false,
        					maskSave : false,
        					postParam : "data",
        					callback: ""
        				  },        
          
        
        postGrid : function( config ){
                      	          	
              Ext.apply( this, config, this.defaults );
              
              var store = this.getStore();
              var records = this.store.getModifiedRecords();                                         
              var save_cnt = 0;
              var fail_cnt = 0;
              var total_cnt = records.length;
              var errors = new Array();
              var alerts = new Array();
              var alert_cnt = 0;
              var conn;               
              var updatePK = this.updatePK;
              var pkCol = this.pkCol;
              var reloadOnChange = this.reloadOnChange;
              var reloadParams = this.reloadParams;
              var maskSave = this.maskSave;
              
                                           
              if ( maskSave == true && total_cnt > 0 ){
              		Ext.MessageBox.show({
              			msg: "Saving data, please wait...",
              			wait: true              		
              		});		             	
              }
              
              
              for( i=0; i<records.length; i++ ) {                                                          	              	  
              	                	  
                  conn = new Ext.data.Connection({ extraParams: this.extraParams });
                                                      
                  this.params = Ext.urlDecode( Ext.urlEncode( records[i].data ) );                                    
                                                      
                  for ( x=0; x<this.dateFields.length; x++ ){
                  		try{
                      		this.params[ this.dateFields[x] ] = records[i].get( this.dateFields[x] ).format( this.dateFormat );
                  		}catch( err ){
                  			
                  		}
                  }                                                                                                                                                            
                   
                  if ( updatePK ){
                  	this.params["GRID_ID"] = records[i].id;
                  }
                  
                  conn.request({
                      url: this.url,            
                      method: "POST",
                      params: this.params,
                      success: function( resp ){
                      		data = Ext.decode( resp.responseText );                      		
                      		if ( data.success == true ){
	                      		if ( updatePK ){                      				                      			
	                      			store.getById( data.grid_id ).set( pkCol, data.id );
	                      			store.getById( data.grid_id ).commit();                      			
	                      		}
	                      		try{
	                      			if( data.alert != null ){
	                      				alerts[alert_cnt] = data.alert;
	                      				alert_cnt ++;
	                      			}
	                      		}catch(err){
	                      			
	                      		}
                      			save_cnt ++;
                      		}else{                      			
                      			try{
                      				errors[fail_cnt] = data.error;                       				
                      			}catch(err){
                      				
                      			}
                      			fail_cnt ++;
                      		}                      			
                      		checkComplete();                      		
                      },                      
                      failure: function(){                      		                      		
                      		fail_cnt ++;   
                      		checkComplete();
                      }                                                                		
                  });
              		                                		                
              }
                                                        
              function checkComplete(){
              		              		       
					if (  ( save_cnt + fail_cnt ) == total_cnt ){                                          		                		              		              			
	              		
	              		if ( fail_cnt > 0 ){
	              			Ext.Msg.alert("Error", fail_cnt + " item(s) failed to save changes. The following is a list of the errors encountered.<br/><br/>" + errors.join("<br/>") );
	              		}else{
	              			store.commitChanges();   			
	              		}
	              			              		
	              		if ( alert_cnt > 0 ){
	              			Ext.Msg.alert("Alert", "The following alerts (" + alert_cnt + ") were generated when saving the data<br/><br/>" + alerts.join("<br/>") );
	              		}	              			              		
	              			              		
	              		if ( reloadOnChange && fail_cnt == 0 ){
							store.reload( { params: reloadParams } );								
						}
						
						if ( maskSave == true ){
              				Ext.MessageBox.hide();              	
              			}
																		
					}											
					
              }                                          
            
        },
        
        postSelectedIDs : function( config ){
                      	          	
            Ext.apply( this, config, this.defaults );
            
        	var store = this.getStore();
            var reloadOnChange = this.reloadOnChange;
            var reloadParams = this.reloadParams;
            var sm = this.getSelectionModel();
        	var rows = sm.selections.items;
        	var id_array = new Array();
        	var postString = "";
        	var postParam = this.postParam;
        	var maskSave = this.maskSave;
        	var callback = this.callback;
                                            
            if ( rows.length > 0 ){
            	
            	if ( maskSave == true ){
              		Ext.MessageBox.show({
              			msg: "Processing, please wait...",
              			wait: true              		
              		});		             	
              	}
            	
				for( i=0; i<rows.length; i++ ) {
					if ( i == 0){
						postString += postParam + "=" + rows[i].data[this.pkCol];
					}else{
						postString += "&" + postParam + "=" + rows[i].data[this.pkCol];
					}		        		 		                 					      					     					      
		        }	            	            										          							            	                                  		        
		        		        
		        var conn = new Ext.data.Connection({
		        	extraParams: this.extraParams
		        });
		        		        
				conn.request({
						url: this.url,            
					    method: "POST",					    
					    params: postString,
					    success: function(){					    	
					    	if ( reloadOnChange ){
								store.reload( { params: reloadParams } );								
							}
							if ( maskSave == true ){
              					Ext.MessageBox.hide();              	
              				}
							if ( callback != "" ){
								callback();
							}
					    },
					    failure: function(){
					    	if ( maskSave == true ){
              					Ext.MessageBox.hide();              	
              				}
					    	Ext.Msg.alert("Alert", "The selected items could not be processed." );					    	
					    } 
				});				
						        
            }else{
            	Ext.Msg.alert("Alert", this.noRowsSelectMsg );                   
            }                                          
            
        },        
        
        deleteSelectedRows : function( config ){
        	
        	Ext.apply( this, config, this.defaults );        	
        	var pk = "";
        	        	
        	rows = this.getSelectionModel().selections.items;
                                            
            if ( rows.length > 0 ){
            	             	
				for( i=0; i<rows.length; i++ ) {												
					
		        		pk = rows[i].data[this.pkCol];
		        		 		        		
		                var conn = new Ext.data.Connection({
		                     extraParams: this.extraParams
		                });
		                  			
		                conn.request({
					         url: this.url,            
					         method: "POST",
					         params: this.pkCol + "=" +  pk,
					         success: this.getStore().remove( rows[i] ),
					         failure: function(){ Ext.Msg.alert("Alert", "One of the selected rows could not be deleted." ); } 
					    });					    		        		 		        		
					      
		        }	            	            										          							            	                  
                                                                                                                                                 		                                                   
            }else{
            	Ext.Msg.alert("Alert", this.noRowsSelectMsg );                   
            }
        	        	
        },
        
        getSelectedRowId: function(config){
        	        	
        	Ext.apply( this, config, this.defaults );
        	rows = this.getSelectionModel().selections.items;        	
        	var row_id = -1;        	
        	if ( rows.length == 1 ){        		
        		row_id = this.params[this.pkCol] = rows[0].data[this.pkCol];
        	}           	     	
        	return row_id;        	        	
        	
        },
        
        addNewRow : function( config ){
        	
        	Ext.apply( this, config, this.defaults );
        	
        	this.stopEditing();
            this.getStore().insert(0, this.rec );
            this.startEditing(0, this.defaultEditCol );
        	
        },
        
        exportGrid: function(config){
        	
        	Ext.apply( this, config, this.defaults );
        	
        	var store = this.getStore();
            var records = this.store.getRange();
            var data = new Array();
            var export_url = this.url;                                    
            
            for( i=0; i<records.length; i++ ) {            	
            	data[i] =  records[i].data;            	            	
            }
                                                            
            var export_form = new Ext.form.FormPanel({
            	            	            	            	            	
            	onSubmit: Ext.emptyFn,
				hidden: true,
            	submit: function() {
                	this.getEl().dom.submit();
            	},
            	
            	items: [
             				{ xtype: "hidden", name: "data", value: Ext.encode( data ) }                         
           		]
            	
            });
                        
            var dh = Ext.DomHelper;
            dh.insertHtml('beforeEnd',document.body,'<div id="ee_grid_exp_form"></div>');
            
            export_form.render('ee_grid_exp_form');
                                    
            export_form.getForm().getEl().dom.action = export_url;
            export_form.getForm().getEl().dom.method = "POST";
            export_form.getForm().submit();                                                                       
                    	            
        }
                               
});