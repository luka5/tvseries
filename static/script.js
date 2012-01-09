Ext.Loader.setConfig({
    enabled: true
});

Ext.application({
    name: 'TvSeries',

    stores: [
        'Episodes',
        'Serials',
        'Season',
        'BroadcastTime',
        'availabilitystore'
    ],

    launch: function() {
        Ext.QuickTips.init();

        var cmp1 = Ext.create('TvSeries.view.TvSeriesViewport', {
            renderTo: Ext.getBody()
        });
        cmp1.show();
        var cmp2 = Ext.create('TvSeries.view.ShowWindow', {
            renderTo: Ext.getBody()
        });
//        cmp2.show();
    }
});

var onload = function(store, grouper, success, operation){
	 if(success || store == undefined)
		return;

	 var data = store.getProxy().getReader().jsonData;
	 if(data.errorInfo != undefined)
		if(data.errorInfo == "Nicht angemeldet.")
			login();
		else
			Ext.Msg.alert('Fehler', data.errorInfo);
};

var login = function(){
	//create new login window
        var cmp3 = Ext.create('TvSeries.view.LoginWindow', {
            renderTo: Ext.getBody()
        });
        cmp3.show();
}