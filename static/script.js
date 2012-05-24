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
    }
});
