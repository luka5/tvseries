/*
 * File: app/view/EpisodesGrid.js
 * Date: Sun Sep 11 2011 23:18:22 GMT+0200 (CEST)
 *
 * This file was generated by Ext Designer version 1.2.0.
 * http://www.sencha.com/products/designer/
 *
 * This file will be generated the first time you export.
 *
 * You should implement event handling and custom methods in this
 * class.
 */

Ext.define('TvSeries.view.EpisodesGrid', {
	extend: 'TvSeries.view.ui.EpisodesGrid',
	alias: 'widget.EpisodesGrid',

	season: null,
	serial: null,
        
	filterTask:  null,

	initComponent: function() {
		var me = this;
		me.callParent(arguments);

		me.down("#addEpisodeButton").on("click", me.addEpisode, me);
		me.down("#addSingleEpisodeButton").on("click", me.addSingleEpisode, me);
		
		this.getSelectionModel().on('select', this.select, this);
		this.down("textfield").on('change', this.search, this);

		this.on('reload', this.reload, this);
                
		this.filterTask = new Ext.util.DelayedTask(this.executeSearch, this);
	},
	
	load: function(serial, season, callback, callbackScope){
		this.season = season;
		this.serial = serial;
		
		this.getStore().getProxy().extraParams = {
			idSeason: this.season.data.id
		};
        
		var loadParams = {};
		if(callback !== undefined && callbackScope !== undefined)
			loadParams = {
				scope: callbackScope,
				callback: function(){
					callback.apply(callbackScope, []);
				}
			};
		this.getStore().load(loadParams);
        
		this.setTitle("3. " + this.serial.data.title + ", " + this.season.data.title);
	},
	
	reload: function(){
		this.filterTask.cancel();
		this.load(this.serial, this.season);
	},
	
	select: function(sm, record, index, opt){
		this.filterTask.cancel();
		this.fireEvent("loadShowWindow", record, this.season, this.serial);
	},
	
	addEpisode: function(){
		var SerialStore = Ext.create('TvSeries.store.Serials',{});
		var SeasonStore = Ext.create('TvSeries.store.Season',{});
		var ReplacementsStore = Ext.create('TvSeries.store.Replacements',{});
		var addwindow = Ext.create('TvSeries.view.AddWindow', {
			renderTo: Ext.getBody(),
			serialStore: SerialStore,
			seasonStore: SeasonStore,
			replacementsStore: ReplacementsStore
		});
		addwindow.show();
		addwindow.on("hide", this.reload, this);
	},

	addSingleEpisode: function(){
		var addSingleEpisodeWindow =Ext.create('TvSeries.view.AddSingleEpisodeWindow', {
			renderTo: Ext.getBody()
		});
		addSingleEpisodeWindow.show();
		addSingleEpisodeWindow.on("hide", this.reload, this);
	},

	search: function(){
		this.filterTask.delay(200);
	},
        
	executeSearch: function(){
		var value = this.down("textfield").getValue();
		this.getStore().getProxy().extraParams = {
			idSeason: this.season.data.id,
			title: value
		};
		this.getStore().load();            
	}         
});