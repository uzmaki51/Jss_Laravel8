var DataSourceTree = function(options) {
	this._data 	= options.data;
	this._delay = options.delay;
}

DataSourceTree.prototype.data = function(options, callback) {
	var self = this;
	var $data = null;

	if(!("name" in options) && !("type" in options)){
		$data = this._data;//the root tree
		callback({ data: $data });
		return;
	}
	else if("type" in options && options.type == "folder") {
		if("additionalParameters" in options && "children" in options.additionalParameters)
			$data = options.additionalParameters.children;
		else $data = {}//no data
	}
	
	if($data != null)//this setTimeout is only for mimicking some random delay
		setTimeout(function(){callback({ data: $data });} , parseInt(Math.random() * 500) + 200);

};

var tree_data = new Object();
for(var index = 0; index < totalList.length; index++) {
	var unit = totalList[index];
	if(index == 0) {
		for (var userIndex = 0; userIndex < unit.members.length; userIndex++) {
			var member = unit.members[userIndex];
			tree_data[member.realname] = {name: '<input type="text" class="hidden" memberType="1" value="' +member.id + '"><i class="icon-archive green"></i>' + member.title + '  ' + member.realname, type: 'item', 'icon-class': 'red'};
		}
	} else {
	 	tree_data[unit.unit] = {name: unit.unit, type: 'folder', 'icon-class': 'blue'};
		if(unit.members.length > 0) {
			tree_data[unit.unit]['additionalParameters'] = new Object();
			tree_data[unit.unit]['additionalParameters']['children'] = new Array();
		}
		if(unit.type == 1) {
			for(var userIndex = 0; userIndex < unit.members.length; userIndex++) {
				var member = unit.members[userIndex];
				tree_data[unit.unit]['additionalParameters']['children'][userIndex] =
				{name: '<input type="text" class="hidden" memberType="1" value="' +member.id + '"><i class="icon-archive green"></i>' + member.title + '  ' + member.realname, type: 'item', 'icon-class': 'red'};
			}
		} else if(unit.type == 2) {
			for(var userIndex = 0; userIndex < unit.members.length; userIndex++) {
				var member = unit.members[userIndex];
				tree_data[unit.unit]['additionalParameters']['children'][userIndex] =
				{name: '<input type="text" class="hidden" memberType="2" value="' +member.id + '"><i class="icon-archive green"></i>' + member.Duty + '  ' + member.realname, type: 'item', 'icon-class': 'red'};
			}
		}

	}
}


var treeDataSource = new DataSourceTree({data: tree_data});


$(document).ready(function () {
	$('#tree').ace_tree({
		dataSource: treeDataSource,
		loadingHTML: '<div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>',
		'open-icon': 'icon-folder-open',
		'close-icon': 'icon-folder-close',
		'selectable': true,
		'selected-icon': null,
		'unselected-icon': null
	});
});