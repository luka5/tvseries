/*
 * File: app/view/ui/TvSeriesViewport.js
 *
 * This file was generated by Ext Designer version 1.2.2.
 * http://www.sencha.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

Ext.define('TvSeries.view.ui.TvSeriesViewport', {
    extend: 'Ext.container.Viewport',
    requires: [
        'TvSeries.view.SerialsGrid',
        'TvSeries.view.SeasonsGrid',
        'TvSeries.view.EpisodesGrid'
    ],

    layout: {
        type: 'border'
    },

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'panel',
                    border: 0,
                    id: 'navigation',
                    width: 250,
                    layout: {
                        align: 'stretch',
                        type: 'vbox'
                    },
                    region: 'west',
                    split: true,
                    items: [
                        {
                            xtype: 'SerialsGrid',
                            flex: 1,
                            store: 'Serials'
                        },
                        {
                            xtype: 'SeasonsGrid',
                            flex: 1
                        }
                    ]
                },
                {
                    xtype: 'EpisodesGrid',
                    region: 'center'
                }
            ]
        });

        me.callParent(arguments);
    }
});