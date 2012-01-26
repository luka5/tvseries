/*
 * File: app/view/ui/AddWindow.js
 *
 * This file was generated by Ext Designer version 1.2.2.
 * http://www.sencha.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

Ext.define('TvSeries.view.ui.AddWindow', {
    extend: 'Ext.window.Window',

    height: 552,
    width: 947,
    layout: {
        type: 'border'
    },
    title: 'Episoden hinzufügen',
    modal: true,

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'form',
                    bodyPadding: 10,
                    url: '../dynamic/?callName=Login',
                    region: 'center',
                    split: true,
                    items: [
                        {
                            xtype: 'textfield',
                            name: 'season',
                            fieldLabel: 'Staffel',
                            anchor: '100%'
                        },
                        {
                            xtype: 'textareafield',
                            height: 207,
                            id: 'code',
                            name: 'code',
                            fieldLabel: 'Json-Code',
                            anchor: '100%'
                        },
                        {
                            xtype: 'textfield',
                            id: 'searchText',
                            name: 'searchText',
                            fieldLabel: 'Ersetzte',
                            anchor: '100%'
                        },
                        {
                            xtype: 'textfield',
                            id: 'replaceText',
                            name: 'replaceText',
                            fieldLabel: 'Mit',
                            anchor: '100%'
                        },
                        {
                            xtype: 'textfield',
                            id: 'allocate',
                            name: 'allocate',
                            value: '\n\{\n"number": "NR_ST",\n"originaltitle": "OT",\n"title": "DT",\n"about": "ZF",\n"originalpremier": "EA",\n"premier": "EAD"\n\}',
                            fieldLabel: 'Zuordnung',
                            anchor: '100%'
                        }
                    ],
                    dockedItems: [
                        {
                            xtype: 'toolbar',
                            anchor: '100%',
                            dock: 'bottom',
                            items: [
                                {
                                    xtype: 'button',
                                    id: 'replaceButton',
                                    text: 'ersetzten'
                                },
                                {
                                    xtype: 'button',
                                    id: 'submitButton',
                                    text: 'hinzufügen'
                                }
                            ]
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }
});