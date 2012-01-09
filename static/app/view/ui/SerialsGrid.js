/*
 * File: app/view/ui/SerialsGrid.js
 * Date: Mon Jan 09 2012 22:32:54 GMT+0100 (CET)
 *
 * This file was generated by Ext Designer version 1.2.2.
 * http://www.sencha.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

Ext.define('TvSeries.view.ui.SerialsGrid', {
    extend: 'Ext.grid.Panel',

    border: 0,
    id: 'seriesGrid',
    title: 'Serien',
    hideHeaders: true,
    store: 'Serials',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            columns: [
                {
                    xtype: 'gridcolumn',
                    id: 'name',
                    width: 250,
                    dataIndex: 'title',
                    text: 'Name'
                }
            ],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [
                        {
                            xtype: 'button',
                            text: 'Hinzufügen'
                        },
                        {
                            xtype: 'tbseparator'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Filtern',
                            labelWidth: 45,
                            size: 10
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }
});