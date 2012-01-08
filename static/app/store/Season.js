/*
 * File: app/store/Season.js
 * Date: Thu Nov 10 2011 00:42:49 GMT+0100 (CET)
 *
 * This file was generated by Ext Designer version 1.2.2.
 * http://www.sencha.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

Ext.define('TvSeries.store.Season', {
    extend: 'Ext.data.Store',

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'Season',
            proxy: {
                type: 'ajax',
                url: '../dynamic/?callName=GetSeasons',
                reader: {
                    type: 'json',
                    idProperty: 'id'
                }
            },
            fields: [
                {
                    name: 'id',
                    type: 'int'
                },
                {
                    name: 'number',
                    type: 'int'
                },
                {
                    name: 'title',
                    type: 'string'
                }
            ]
        }, cfg)]);
    }
});