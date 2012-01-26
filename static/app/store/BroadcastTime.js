/*
 * File: app/store/BroadcastTime.js
 *
 * This file was generated by Ext Designer version 1.2.2.
 * http://www.sencha.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

Ext.define('TvSeries.store.BroadcastTime', {
    extend: 'Ext.data.Store',

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'BroadcastTime',
            proxy: {
                type: 'ajax',
                url: '../dynamic/?callName=GetBroadcastTime',
                reader: {
                    type: 'json'
                }
            },
            fields: [
                {
                    name: 'channel',
                    type: 'string'
                },
                {
                    name: 'time',
                    type: 'string'
                },
                {
                    name: 'title',
                    type: 'string'
                }
            ]
        }, cfg)]);
    }
});