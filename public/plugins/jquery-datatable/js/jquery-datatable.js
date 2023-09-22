"use strict";!function(t){function e(e,n,l){s=e,a=n,o=l,r=o,q(o)||(r=o.split(",")),d=t("#"+s.settings.id+' [name="'+a+'"]'),d.map(function(){this.checked=!1}),d.map(function(){r.find(t=>t==this.value)&&(this.checked=!0)}),u=t("#jsd-more-filters-"+s.settings.id+' [name="M-'+a+'"]'),u.length&&(u.map(function(){this.checked=!1}),u.map(function(){r.find(t=>t==this.value)&&(this.checked=!0)}));var i=m(e.settings.filterControl,"key",n);if(i.length){var s,a,o,r,d,u,c,h,_=l;if(q(l)||(_=l.split(",")),l.length){var f=p(i[0].choices,_.join(","));g(e.settings.id,n,i[0].title,f)}else{c=e.settings.id,h=n,$("#"+c+' [data-filter="'+h+'"]').length&&$("#"+c+' [data-filter="'+h+'"]').remove(),t("#"+c+" "+B(W.activeFilterTable)).toggleClass(W.hidden,!t("#"+c+" "+B(W.filterTableItem)).length)}}}function n(t){var e=null,n='<div class="'+W.dropdown+'" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" data-dropdown-filter="'+t.key+'">';n+='<ul class="E0991-UL" role="menu">',N(t.value)||q(e=t.value)||(e=e.split(","));for(let i=0;i<t.choices.length;i++)n+=l(t,e,i);return t.showClear&&(n+='<li><button type="button" class="'+[W.btn,W.dropdownMenuItem,W.buttonLink,W.clearFilterButton].join(" ")+'" data-name="'+t.key+'">Clear</button></li>'),n+="</ul>",n+="</div>"}function l(t,e,n){let l=t.choices[n];var i=l.value.toString().replace(/ /g,"-"),s="<li>";return s+='<label for="jsdFilter-'+t.key+"-"+i+'" class="'+[W.label,W.dropdownMenuItem,W.dropdownMenuButton].join(" ")+'">',t.allowMultiple?s+='<input id="jsdFilter-'+t.key+"-"+i+'" value="'+l.value+'" name="'+t.key+'" type="checkbox" class="'+W.inpt+" "+W.checkboxIpt+" "+W.filterItem+'" '+(e&&e.includes(l.value)?"checked":"")+">":s+='<input id="jsdFilter-'+t.key+"-"+i+'" value="'+l.value+'" name="'+t.key+'" type="radio" class="'+W.inpt+" "+W.radioIpt+" "+W.filterItem+'" '+(e&&e.includes(l.value)?"checked":"")+">",s+="<span>"+l.label+"</span>",s+="</label>",s+="</li>"}function i(t,e){var n='<div class="'+W.dropdown+'" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" data-name="'+e+'">';n+='<ul class="E0991-UL" role="menu">';for(let l=0;l<t.length;l++){let i=t[l];status;var s=[W.moreActionLink,W.dropdownMenuItem];"primary"===i.status?s.push(W.textPrimary):"error"===i.status?s.push(W.textError):s.push(W.textDefault),n+="<li>",n+='<a class="'+s.join(" ")+'" data-id="'+l+'">'+i.title+"</a>",n+="</li>"}return n+="</ul>",n+="</div>"}function s(e){e.preventDefault();var n=t(e.target).closest(B(W.dropdownControl));if(!n.length)return!1;var l=n.find(B(W.dropdown));if(!l.length)return!1;l.hasClass(W.dropdownOpen)?l.removeClass(W.dropdownOpen):(t(B(W.dropdown)).removeClass(W.dropdownOpen),l.toggleClass(W.dropdownOpen))}function a(){var e=t("#"+U.moreFilterPre+this.settings.id);e.length&&e.toggleClass("E0991-Open")}function o(e){var n=t("#jsd-more-filters-"+this.settings.id+' [name="'+e.target.name+'"]:checked').map(function(){return this.value}).get(),l=e.target.name.replace("M-","");this.setFilter(l,n)}function r(t,e){var n=null,l='<li class="'+W.moreFilterMenuItem+'" data-dropdown-filter="'+e.key+'">';l+='<div class="'+W.moreFilterItemTitle+'">',l+='<h2 class="E0991-TITLE2">'+e.title+"</h2>",e.showClear&&(l+='<button type="button" class="'+[W.btn,W.clearFilterButton].join(" ")+'" data-name="'+e.key+'">Clear</button>'),l+="</div>",l+='<div class="'+W.moreFilters+'">',l+='<ul class="E0991-UL">',N(e.value)||q(n=e.value)||(n=n.split(","));for(let i=0;i<e.choices.length;i++)l+=d(e,n,i);return l+="</ul>",l+="</div>",l+="</li>"}function d(t,e,n){let l=t.choices[n];var i=l.value.toString().replace(/ /g,"-"),s="<li>";return s+='<label for="jsdMoreFilter-'+t.key+"-"+i+'" class="'+[W.label,W.dropdownMenuItem].join(" ")+'">',t.allowMultiple?s+='<input id="jsdMoreFilter-'+t.key+"-"+i+'" value="'+l.value+'" name="M-'+t.key+'" type="checkbox" class="'+W.inpt+" "+W.checkboxIpt+" "+W.moreFilterItem+'" '+(e&&e.includes(l.value)?"checked":"")+">":s+='<input id="jsdMoreFilter-'+t.key+"-"+i+'" value="'+l.value+'" name="M-'+t.key+'" type="radio" class="'+W.inpt+" "+W.radioIpt+" "+W.moreFilterItem+'" '+(e&&e.includes(l.value)?"checked":"")+">",s+="<span>"+l.label+"</span>",s+="</label>",s+="</li>"}function u(t,e){if(null!==e.prev&&_("#"+t+" "+B(W.paginationControl)+'[data-action="previous"]',!1),null!==e.next&&_("#"+t+" "+B(W.paginationControl)+'[data-action="next"]',!1),e.items.length){var n=e.start+1,l=e.start+e.items.length;$("#"+t+" "+B(W.paginationResultInfo)).find("span").html("Showing "+n+" to "+l+" of "+e.results+" results")}else $("#"+t+" "+B(W.paginationResultInfo)).find("span").html("No results found!");var i=(e.results-e.results%e.limit)/e.limit;i=e.results%e.limit?i+1:i,$("#"+t+" "+B(W.paginationPageSelectControl)).attr("disabled",!1),1===e.page?$("#"+t+" "+B(W.paginationPageSelectControl)).html('<option value="1" selected="true">1</option>'):$("#"+t+" "+B(W.paginationPageSelectControl)).html('<option value="1">1</option>');for(let s=2;s<=i;s++)s===e.page?$("#"+t+" "+B(W.paginationPageSelectControl)).append('<option value="'+s+'" selected="true">'+s+"</option>"):$("#"+t+" "+B(W.paginationPageSelectControl)).append('<option value="'+s+'">'+s+"</option>")}function c(e,n=null){var l=t("#"+e.settings.id),i=l.find("tbody");if(!e.settings.items.length){l.addClass(W.noResults);var s=t('<tr role="row">'),a=t('<td colspan="'+(function t(e,n,l){for(var i=[],s=0;s<e.length;s++)e[s][n]==l&&i.push(e[s]);return i}(e.settings.columns,"hidden",!1).length+(e.settings.selectable?1:0))+'">'),o="No results found!",r=e.settings.language.resultsTitle;e.settings.resourceName.plural&&(r=e.settings.resourceName.plural);var o=e.settings.language.noResultsFoundTitle.replace("_RESOURCE_",r);n&&(a.addClass("E0991-text-danger"),o=n),a.append("<div><span>"+o+"</span></div>"),s.append(a),i.append(s);return}l.removeClass(W.noResults);for(let d=0;d<e.settings.items.length;d++){var u=e.settings.items[d],s=t('<tr role="row">');if(e.settings.selectable)var c=t('<td class="E0991-Checkbox"><div><input value="'+d+'" name="TableRow" type="checkbox" class="'+W.inpt+" "+W.checkboxIpt+'"></div></td>');s.append(c);for(let g=0;g<e.settings.columns.length;g++){var p=e.settings.columns[g],a=t("<td>");if(p.classes&&a.addClass(p.classes),p.hidden&&a.addClass(W.hidden),!p.hidden){if(p.element&&D(p.element)){var h=p.element(u,l,e.settings.items);H(h)?a.html(h):P(e.settings,0,"Element is not a dom element, "+p.key)}else u.hasOwnProperty(p.key)||P(e.settings,0,"Undefined row value: "+p.key),a.html(u[p.key])}s.append(a)}i.append(s),e.settings.onRowClick&&D(e.settings.onRowClick)&&s.on("click",function(t){"TableRow"!==t.target.name&&e.settings.onRowClick(u,e,this)})}t(document).delegate('[name="TableRow"]',"change",v.bind(e))}function g(e,n,l,i){var s,a=q(i)?i.join(", "):i,l=l||(s=n.replace("_"," "),"string"!=typeof s?"":s.charAt(0).toUpperCase()+s.slice(1)),o=t("#"+e+" "+B(W.activeFilterTable));if(t("#"+e+' [data-filter="'+n+'"]').length)t("#"+e+' [data-filter="'+n+'"]').find("span.description").html(l+" "+a);else{var r='<li class="'+W.filterTableItem+'" data-filter="'+n+'">';r+='<div class="E0991-Filter-Table-Item-2">',r+='<span class="description">'+l+" "+a+"</span>",r+='<button type="button" class="'+W.btn+" "+W.filterRemoveButton+'">',r+='<svg class="'+W.svg+' jsd0-h-5 jsd0-w-5" viewBox="0 0 20 20" fill="currentColor">',r+='<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />',r+="</svg></button></div></li>",o.find("ul").append(r)}o.toggleClass(W.hidden,!o.find("li").length)}function p(t,e){var n=e,l=[];q(e)||(n=e.split(","));for(let i=0;i<t.length;i++){let s=t[i];n.find(t=>t==s.value)&&l.push(s.label)}return l}function h(e){var n=t(e.target).closest("li");if(n.length){var l=n.data("filter");this.setFilter(l,[])}}function _(t,e){C($(t),"jsd0-opacity-50 jsd0-cursor-not-allowed",e),$(t).attr("disabled",e)}function f(t){var e="";for(var n in t)""!=e&&(e+="&"),e+=n+"="+encodeURIComponent(t[n]);return e}function v(e){var n=t('[name="'+e.target.name+'"]:checked').length;if(t(e.target).closest("tr").toggleClass(W.rowSelected,e.target.checked),n){t(S(this.settings.id)+" "+B(W.selectAllToggler))[0].checked||(t(S(this.settings.id)+" "+B(W.selectAllContainer)).toggleClass(W.activeBulkActions,!0),t(S(this.settings.id)+" "+B(W.selectAllToggler))[0].checked=!0);var l=n+" "+this.settings.language.selectedTitle;if(this.settings.resourceName.plural&&this.settings.resourceName.singular){var i,s,a,o=(i=n,s=this.settings.resourceName.singular,a=this.settings.resourceName.plural,i>1?a:s);l=n+" "+o+" "+this.settings.language.selectedTitle}t(S(this.settings.id)).find(B(W.selectAllLabel)).html(l)}else t(S(this.settings.id)+" "+B(W.selectAllToggler))[0].checked&&(t(S(this.settings.id)+" "+B(W.selectAllContainer)).toggleClass(W.activeBulkActions,!1),t(S(this.settings.id)+" "+B(W.selectAllToggler))[0].checked=!1);t("#"+this.settings.id).trigger(X.SELECTION,this)}function b(e){var n=this.settings;e.preventDefault();var l=t(e.target).closest(B(W.selectAllContainer));if(l.length){l.toggleClass(W.activeBulkActions,e.target.checked),t("#"+n.id+' [name="TableRow"]').map(function(){this.checked=e.target.checked,t(this).parents("tr").toggleClass(W.rowSelected,e.target.checked)});var i=t("#"+n.id+' [name="TableRow"]:checked').length,s=i+" "+n.language.selectedTitle;if(n.resourceName.plural&&n.resourceName.singular){var a,o,r,d=(a=i,o=n.resourceName.singular,r=n.resourceName.plural,a>1?r:o);s=i+" "+d+" "+n.language.selectedTitle}l.find(B(W.selectAllLabel)).html(s)}t("#"+n.id).trigger(X.SELECTION,this)}function m(t,e,n){var l=[];for(let i=0;i<t.length;i++)t[i][e]==n&&l.push(t[i]);return l}function C(t,e,n){t&&t.length&&(n?t.addClass(e):t.removeClass(e))}function k(e){$(e.target).closest(B(W.dropdownControl)).length||t(B(W.dropdown)).removeClass(W.dropdownOpen)}function E(e){let n=t(e.target).parents(B(W.dropdown));if(n.length){var l=n.data("name");let i=this.settings[l][e.target.dataset.id];if(i&&i.onAction&&D(i.onAction)){if("bulkActions"===l){var s,a=w(this);i.onAction(a,this)}else i.onAction(this);s&&s.length?t(B(W.dropdown)).map(function(){this.id!==s[0].id&&$(this).removeClass("open")}):t(B(W.dropdown)).removeClass(W.dropdownOpen)}}}function y(t){let e=this.settings.buttons[t.target.dataset.id];e&&e.action&&D(e.action)&&e.action(this)}function w(e){return t("#"+e.settings.id+' [name="TableRow"]:checked').map(function(){return e.settings.items[this.value]}).get()}function T(e){var n=t(e.target).closest(B(W.paginationControl)).data("action");if("previous"===n)var l=this.settings.page-1;if("next"===n)var l=this.settings.page+1;l&&this.getData({page:l})}function F(t){this.getData({page:parseInt(t.target.value)})}function A(e){var n=this;n.settings.filterControl.map(function(t){n.setFilter(t.key,"",!1)}),t(S(n.settings.id)).find('[name="queryValue"]').val("");var l=t(e.target).closest(B(W.tabLink)),i=parseInt(l.data("tab-index"));if(this.settings.filterTabs[i]){var s={};for(let a=0;a<n.settings.filterTabs[i].filters.length;a++){var o=n.settings.filterTabs[i].filters[a];s[o.key]=o.value}n.setFilters(s)}l.parents(B(W.tabContainer)).find(B(W.tabLink)).map(function(){t(this).removeClass("active")}),l.addClass("active")}function I(e){var n=t("#"+this.settings.id+' [name="'+e.target.name+'"]').map(function(){if(this.checked)return this.value}).get();this.setFilter(e.target.name,n)}function M(e){t(e.target).closest("[data-dropdown-filter]").find("input:checked").length&&this.setFilter(e.target.dataset.name,[])}function x(e){var n={};t("#jsd-more-filters-"+e.settings.id+" "+B(W.moreFilterItem)+":checked").map(function(){var t=this.name.replace("M-",""),e=this.value;n.hasOwnProperty(t)||(n[t]=[]),n[t].push(e)});var l=t("#"+e.settings.id+' [name="queryValue"]').val();return l&&(n.queryValue=l),e.settings.sortValue&&(n.sortValue=e.settings.sortValue),t("#"+e.settings.id+' [name="sortValue"]').length&&t("#"+e.settings.id+' [name="sortValue"]').map(function(){this.checked&&(n.sortValue=this.value)}),n}function L(t,e){var n="";if(t.settings.yajra)return t.settings.columns.map(function(t,l){var i=e.hasOwnProperty(t.key);n+=encodeURIComponent("columns["+l+"][data]")+"="+t.key+"&";var s=t.name||t.key;n+=encodeURIComponent("columns["+l+"][name]")+"="+s+"&",n+=encodeURIComponent("columns["+l+"][searchable]")+"="+t.searchable+"&",n+=encodeURIComponent("columns["+l+"][orderable]")+"="+t.orderable+"&",n+=encodeURIComponent("columns["+l+"][search][value]")+"="+(i?e[t.key]:"")+"&",n+=encodeURIComponent("columns["+l+"][search][regex]")+"=false&"}),n+=encodeURIComponent("search[value]")+"="+(e.queryValue||"")+"&",n+=encodeURIComponent("search[regex]")+"=false&";if(e.hasOwnProperty("sortValue")){var l=e.sortValue.split("__");2===l.length&&(n+=encodeURIComponent("sort[column]")+"="+l[0]+"&",n+=encodeURIComponent("sort[dir]")+"="+l[1])}return n}function j(t){this.getData({page:1})}function R(t,e,n){return t>1?n:e}function B(t){return"."+t}function S(t){return"#"+t}function P(t,e,n){if(n="DataTables warning: "+n,e)window.console&&console.log&&console.log(n);else{var l=t.errorMode;if("alert"==l)alert(n);else if("throw"==l)throw Error(n);else"function"==typeof l&&l(t,n)}}function q(t){return t&&"object"==typeof t&&t.constructor===Array}function D(t){return"function"==typeof t}function N(t){return null===t||""===t}function O(t){return t&&"object"==typeof t&&t.constructor===Object}function H(t){return!!(t instanceof HTMLElement||t[0]instanceof HTMLElement)}window.JsDataTables=[],J=function(n){return this.settings=n.settings,this.jqXHR=null,this.getData=function(e){var n=this;n.settings.page,n.settings.start=0;var l={page:n.settings.page,limit:n.settings.limit,length:n.settings.limit,start:n.settings.start};return n.settings.query&&O(n.settings.query)&&(l={...l,...n.settings.query}),e&&O(e)&&(l={...l,...e}),l.page>1&&(l.start=l.length*l.page-l.length,n.settings.start=l.start),l={...l,...x(n)},n.jqXHR&&n.jqXHR.abort(),n.jqXHR=$.ajax({type:"get",url:n.settings.src+"?"+f(l)+"&"+L(n,l),beforeSend:function(e){if(n.settings.ajaxHeaders.length)for(let l=0;l<n.settings.ajaxHeaders.length;l++){let i=n.settings.ajaxHeaders[l];e.setRequestHeader(i.title,i.value)}t("#"+n.settings.id).find("tbody").html(""),C(t("#"+n.settings.id).find("tfoot tr"),W.hidden,!1),t(S(n.settings.id)+" "+B(W.paginationControl)+'[data-action="previous"]').attr("disabled",!0),t(S(n.settings.id)+" "+B(W.paginationControl)+'[data-action="next"]').attr("disabled",!0),$(S(n.settings.id)+" "+B(W.paginationResultInfo)).find("span").html("Loading..."),t(S(n.settings.id)+" "+B(W.paginationPageSelectControl)).attr("disabled",!0),t(S(n.settings.id)+' [name="JsdSelectAllRow"]:checked').length&&t(S(n.settings.id)+' [name="JsdSelectAllRow"]').trigger("click"),t(S(n.settings.id)+' [name="JsdSelectAllRow"]').attr("disabled",!0)},success:function(e){t("#"+n.settings.id).trigger(X.TABLE_DRAW_START,n),n.jqXHR=null,n.settings.items=(e.data||[]).slice(0,n.settings.limit),n.settings.results=e.recordsFiltered,e.recordsFiltered&&e.hasOwnProperty("draw")?(n.settings.yajra=!0,n.settings.page=n.settings.start/n.settings.limit+1,n.settings.next=n.settings.start+n.settings.limit<e.recordsFiltered?n.settings.start+n.settings.limit:null,n.settings.prev=n.settings.start?n.settings.start-n.settings.limit:null):(n.settings.page=e.current_page||1,n.settings.next=e.next_page_url?n.settings.page+1:null,n.settings.prev=e.prev_page_url?n.settings.page-1:null),u(n.settings.id,n.settings),C(t("#"+n.settings.id).find("tfoot tr"),W.hidden,!0);var l=null;!e.data.length&&e.error&&(l=e.error),c(n,l),t(S(n.settings.id)+' [name="JsdSelectAllRow"]').attr("disabled",!n.settings.items.length),t("#"+n.settings.id).trigger(X.TABLE_DRAW_FINISH,n)},error:function(e){if("abort"!==e.statusText){n.jqXHR=null,n.settings.next=null,n.settings.prev=null,u(n.settings.id,n.settings),C($("#"+n.settings.id).find("tfoot tr"),W.hidden,!0);var l=null;403!==e.status&&(l="Error: "+e.responseJSON.message,P(n.settings,0,"Ajax error")),c(n,l),t("#"+n.settings.id).trigger(X.TABLE_DRAW_FINISH,n)}t(S(n.settings.id)+' [name="JsdSelectAllRow"]').attr("disabled",!n.settings.items.length)}}),n},this.refresh=function(){return this.getData({page:1}),this},this.setFilter=function(t,n,l=!0){return e(this,t,n),l&&this.getData({page:1}),this},this.setQuery=function(t,e=!0){return this.settings.query={...this.settings.query,...t},e&&this.getData({page:1}),this},this.removeQuery=function(t,e=!0){return delete this.settings.query[t],e&&this.getData({page:1}),this},this.getQuery=function(t=!1){var e=this,n={limit:-1,start:0};return e.settings.query&&O(e.settings.query)&&(n={...n,...e.settings.query}),n.page>1&&(n.start=n.length*n.page-n.length,e.settings.start=n.start),f(n={...n,...x(e)})+"&"+L(e,n)},this.setFilters=function(n,l=!0){var i=this;return O(n)&&(t.each(n,function(t,n){e(i,t,n)}),l&&this.getData({page:1})),this},this.setSort=function(e){return function e(n,l){t("#"+n.settings.id+' [name="jsdFilter-sortValue"]').map(function(){this.value===l&&(this.checked=!0)})}(this,e),this.getData({page:1}),this},this.loadFilters=function(t,e){for(var n=$("#"+this.settings.id).find('[data-dropdown-filter="'+t+'"]'),i=$("#"+U.moreFilterPre+this.settings.id).find('[data-dropdown-filter="'+t+'"]'),s=0;s<this.settings.filterControl.length;s++)if(this.settings.filterControl[s].key===t){var a=this.settings.filterControl[s];this.settings.filterControl[s].choices=e;var o=null;N(a.value)||q(o=a.value)||(o=o.split(",")),n.find("ul").html(""),i.find("ul").html("");for(let r=0;r<e.length;r++)n.length&&n.find("ul").append(l(a,o,r)),i.length&&i.find("ul").append(d(a,o,r));if(a.showClear){var u='<li><button type="button" class="'+[W.btn,W.dropdownMenuItem,W.buttonLink,W.clearFilterButton].join(" ")+'" data-name="'+a.key+'">Clear</button></li>';n.find("ul").append(u)}}},this.getFilters=function(t=!1){this.settings.page;var e={page:this.settings.page,limit:this.settings.limit};return(this.settings.query&&O(this.settings.query)&&(e={...e,...this.settings.query}),e={...e,...x(this)},t)?f(e):e},this.items=function(){return this.settings.items||[]},this.selected=function(){return w(this)},this.findItem=function(t,e){return this.settings.items.find(function(n){return n[t]==e})||null},this},V=function(e){var l=this;return this.api=function(){return new J(this)},this.each(function(){var d=function t(e){for(let n=0;n<JsDataTables.length;n++){let l=JsDataTables[n];if(l.settings.id===e)return l}return!1}(this.id);if(d)return d;var u=t.extend({},z,e),c=6;if(u.src&&(u.serverSide=!0),u.columns.length&&t.each(u.columns,function(t,e){u.columns[t]={...U.column,...e}}),u.language&&O(u.language)?u.language={...U.language,...u.language}:u.language=U.language,u.filterTabs&&u.filterTabs.length?(t.each(u.filterTabs,function(t,e){if(u.filterTabs[t]={...U.filterTab,...e},!u.filterTabs[t].label){P(u,0,"Tab no label");return}}),void 0===u.filterTabs[u.activeTab]&&(u.activeTab=0)):(u.activeTab=null,delete u.filterTabs),u.filterControl.length&&t.each(u.filterControl,function(t,e){u.filterControl[t]={...U.filterControl,...e}}),u.sortControl&&O(u.sortControl)&&(u.sortControl={...U.sortControl,...u.sortControl},t.each(u.sortControl.options,function(t,e){u.sortControl.options[t]={...U.sortControlOption,...e}})),u.sortOptions.length&&t.each(u.sortOptions,function(t,e){u.sortOptions[t]={...U.sortOption,...e}}),u.bulkActions.length&&t.each(u.bulkActions,function(t,e){u.bulkActions[t]={...U.bulkAction,...e}}),u.moreActions.length&&t.each(u.moreActions,function(t,e){u.moreActions[t]={...U.moreAction,...e}}),u.buttons.length&&t.each(u.buttons,function(t,e){u.buttons[t]={...U.button,...e}}),u.ajaxHeaders&&t.each(u.ajaxHeaders,function(t,e){u.ajaxHeaders[t]={...U.ajaxHeader,...e}}),u.classes||(u.classes={}),u.classes=t.extend({},W,u.classes),u.id=this.id,u.id||(this.id=U.idPre+Math.floor(6*Math.random()),u.id=this.id),!function t(e,n,l,i=1){return!e&&(P(n,i,l),!0)}(u.columns.length,u,"No columns defined")){l.settings=Object.assign({},u);var _=l.api();if(function e(l,s,o){t(l).addClass("js-datatable");var d,u,c,h=o.filterControl.length,_=!1;if(t(l)[0].className=W.container,o.filterTabs){var f='<div class="'+W.tabContainer+'">';f+='<ul class="E0991-UL">';for(let v=0;v<o.filterTabs.length;v++){var b=o.filterTabs[v],C=W.tabLink;v==o.activeTab&&(C+=" active"),f+="<li>",f+='<a class="'+C+'" data-tab-name="'+b.label+'" data-tab-index="'+v+'">',f+='<span class="'+W.tabLabel+'">'+b.label+"</span>",f+="</a>",f+="</li>"}f+="</ul>",f+="</div>",t(l).append(f)}if(o.showSearchQuery||h||o.sortControl||o.moreActions.length){var k='<div class="'+W.filtersContainer+'">';if(o.showSearchQuery&&(k+='<div class="'+W.queryValueContainer+'">',k+='<div class="'+W.queryIconContainer+'">',k+='<span><svg class="'+W.svg+'" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg></span>',k+="</div>",k+='<input type="text" name="queryValue" class="'+W.inpt+" "+W.textIpt+" "+W.queryValueInput+'" data-toggle="filter">',k+="</div>"),h){var E=m(o.filterControl,"shortcut",!0),y='<div class="'+W.filterControlContainer+'">';if(y+='<div class="'+W.filterControlGroup+'">',_=E.length<h,E.length)for(let w=0;w<E.length;w++){var A=E[w],I='<div class="'+(A.hidden?"":W.filterControlItem+" "+W.dropdownControl)+" "+(A.hidden?W.hidden:"")+'">';I+='<button type="button" class="'+W.btn+" "+W.button+" "+W.dropdownControlButton+'" data-id="'+A.key+'">'+A.title,I+='<svg class="'+W.svg+'" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>',I+="</button>",I+=n(A),I+="</div>",y+=I}y+="</div></div>";var M=[W.btn,W.button,"E0991-ml-2",W.moreFilterButtonOpen];_||M.push("lg:E0991-hidden");var x=_?o.language.moreFiltersTitle:o.language.filtersTitle;y+='<button type="button" class="'+M.join(" ")+'" aria-expanded="false" aria-haspopup="true">'+x+"</button>",k+=y}if(o.sortControl&&o.sortControl.options.length){var L='<div class="'+o.classes.sortControlItem+" "+o.classes.dropdownControl+' E0991-ml-2">';L+='<button type="button" class="'+W.btn+" "+o.classes.button+" "+o.classes.dropdownControlButton+'" data-id="sortValue">',L+='<svg class="'+W.svg+' E0991-rotate-90 E0991-mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"></path></svg>'+o.language.sortTitle+"</button>",L+=n({key:"sortValue",choices:o.sortControl.options,value:o.sortControl.value||o.sortControl.options[0].value}),L+="</div>",k+=L}if(o.moreActions.length){var R='<div class="'+o.classes.dropdownControl+' E0991-ml-2">';R+='<button type="button" class="'+W.btn+" "+o.classes.iconButton+" "+o.classes.dropdownControlButton+'" aria-expanded="false" aria-haspopup="true" data-id="moreActions">',R+='<svg class="'+W.svg+'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg></button>',R+=i(o.moreActions,"moreActions"),R+="</div>",k+=R}if(o.buttons.length){var S='<div class="'+W.filterControlGroup+' E0991-ml-2">';for(let P=0;P<o.buttons.length;P++){var q=o.buttons[P];q.classList.push(W.btn,W.button,W.btnAction,W.btn+"-"+q.style),S+='<button type="button" class="'+q.classList.join(" ")+'" data-id="'+P+'">'+q.label+"</button>"}S+="</div>",k+=S}k+="</div>",t(l).append(k),t("#"+o.id+' [name="queryValue"]').keyup(j.bind(s))}o.filterControl.length&&(t(l).append('<div class="'+[W.activeFilterTable,W.hidden].join(" ")+'"><ul class="jsdFiltersTable E0991-UL"></ul></div>'),function t(e,n){for(let l=0;l<n.length;l++){let i=n[l];if(!N(i.value)){var s=p(i.choices,i.value);g(e,i.key,n.title,s)}}}(o.id,o.filterControl),function e(n,l){var i=U.moreFilterPre+n.settings.id,s='<div class="'+W.moreFiltersContainer+'" id="'+i+'">';s+='<div class="'+W.moreFiltersModal+'">',s+='<div class="'+W.moreFiltersModalContent+'">',s+='<div class="'+W.moreFiltersModalCard+'">',s+='<div class="'+W.moreFiltersModalHeader+'">',s+='<h2 class="E0991-TITLE2">'+n.settings.language.moreFiltersTitle+"</h2>",s+='<button type="button" class="'+W.btn+" "+W.closeMoreFiltersButton+'">',s+='<svg class="'+W.svg+'" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">',s+='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>',s+="</div>",s+='<div class="'+W.moreFiltersList+'">',s+='<ul class="E0991-UL">';for(let o=0;o<l.length;o++)s+=r(n,l[o]);s+="</ul>",s+="</div>",s+="</div>",s+="</div>",s+="</div>",s+="</div>",t("body").append(s),t("#"+i).on("click",function(e){t(e.target).closest(B(W.moreFiltersModalCard)).length||t("#"+U.moreFilterPre+n.settings.id).toggleClass("E0991-Open")}),t(B(W.closeMoreFiltersButton)).on("click",a.bind(n))}(s,o.filterControl));var D=t("<table><thead></thead><tbody></tbody><tfoot></tfoot></table>"),O=t('<div class="'+W.tableContainer+'">').append(D);if(t(l).append(O),o.table=D[0],o.columns.length){var H,V,J=t("<tr>"),z=0;o.selectable&&J.append((H=o,V='<th class="E0991-Checkbox">',V+='<div class="'+W.selectAllContainer+'">',V+='<div class="'+W.selectAllWrapper+'">',V+='<div class="'+W.selectAllGroupButton+'">',V+='<label for="Jsd-Datatable-'+H.id+'" class="'+W.label+" "+(H.bulkActions.length?"E0991-l-md":"E0991-Rounded")+'"><input id="Jsd-Datatable-'+H.id+'" name="JsdSelectAllRow" type="checkbox" class="'+W.inpt+" "+W.checkboxIpt+" "+W.selectAllToggler+'">',V+='<span class="'+W.selectAllLabel+'">2 '+H.language.selectedTitle+"</span></label>",H.bulkActions.length&&(V+='<div class="'+H.classes.dropdownControl+'">',V+='<button type="button" class="'+W.btn+" "+H.classes.dropdownControlButton+' E0991-Rounded-None E0991-r-md" aria-expanded="false" aria-haspopup="true" data-id="bulkActions"><span>'+H.language.moreActionTitle+"</span>",V+='<svg class="'+W.svg+'" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></button>',V+=i(H.bulkActions,"bulkActions"),V+="</div>"),V+="</div>",V+="</div>",V+="</div>",V+="</th>"));for(let X=0;X<o.columns.length;X++){let G=o.columns[X];var Q=t("<th>");G.classes&&Q.addClass(G.classes),G.titleHidden||Q.html("<span>"+G.title+"</span>"),G.hidden&&(Q.addClass(W.hidden),z++),J.append(Q)}t(o.table.tHead).append(J),function e(n,l){for(var i="",s=0;s<3;s++){i+="<tr>";for(var a=0;a<l;a++){var o=[W.skeletonLine],r="";a||(r="E0991-Checkbox"),i+='<td class="'+r+'"><div class="'+o.join(" ")+'"></div></td>'}i+="</tr>"}t(n).append(i)}(o.table.tFoot,o.columns.length+(o.selectable?1:0)-z)}o.allowPagination&&(d=l,u=s,c='<div class="'+W.paginationContainer+'">',c+='<div class="'+W.paginationResultInfo+'">',c+="<span>Loading...</span>",c+="</div>",c+='<div class="'+W.paginationNav+'">',c+='<button type="button" class="'+[W.btn,W.paginationControl,W.button].join(" ")+'" data-action="previous">',c+='<svg class="'+W.svg+'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>',c+="</button>",c+='<select class="'+[W.btn,W.paginationPageSelectControl,W.button].join(" ")+'">',c+='<option value="1">1</option>',c+="</select>",c+='<button type="button" class="'+[W.btn,W.paginationControl,W.button].join(" ")+'" data-action="next">',c+='<svg class="'+W.svg+'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>',c+="</button>",c+="</div>",c+="</div>",t(d).append(c),t(B(W.paginationControl)).on("click",T.bind(u)),t(B(W.paginationPageSelectControl)).on("change",F.bind(u)))}(this,_,u),JsDataTables.push(_),u.serverSide){if(!N(u.activeTab)){var f=u.filterTabs[u.activeTab];_.setFilters(f.filters,!1)}u.load&&_.getData()}t(document).delegate(B(W.dropdownControlButton),"click",s.bind(u)),t(document).on("click",k.bind(u)),t(document).delegate(B(W.moreFilterButtonOpen),"click",a.bind(_)),t(document).delegate(B(W.selectAllToggler),"change",b.bind(_)),t(document).delegate(B(W.filterRemoveButton),"click",h.bind(_)),t(document).delegate(B(W.filterItem),"change",I.bind(_)),t(document).delegate(B(W.clearFilterButton),"click",M.bind(_)),t(document).delegate(B(W.moreFilterItem),"change",o.bind(_)),t(document).delegate(B(W.moreActionLink),"click",E.bind(_)),t(document).delegate(B(W.tabLink),"click",A.bind(_)),t(document).delegate(B(W.btnAction),"click",y.bind(_))}}),this};var V,J,z={src:"",resourceName:{singular:"",plural:""},queryValue:"",showSearchQuery:!0,load:!0,query:{},sortOptions:[],sortValue:"",order:[],showHeader:!0,columns:[],selectable:!0,allowPagination:!0,loading:!1,items:[],page:1,start:0,limit:50,next:!1,prev:!1,results:0,filterControl:[],bulkActions:[],errorMode:"throw",hoverable:!0,moreActions:[],serverSide:!1,jqXHR:null,ajaxHeaders:[],onRowClick:null,activeTab:null,language:{},yajra:!1,buttons:[]};let U={column:{key:"",title:"",name:"",classes:"",searchable:!0,orderable:!0,titleHidden:!1,hidden:!1,element:""},filterTab:{label:"",filters:[]},filterTabFilters:{key:"",value:""},filterControl:{key:"",label:"",titleHidden:!1,filter:"",shortcut:!0,reloadOnChange:!0,allowMultiple:!0,showClear:!0,onBefore:"",onChange:"",value:null,hidden:!1},moreAction:{title:"",onAction:"",status:"default"},sortControl:{options:[],value:""},sortControlOption:{label:"",value:""},bulkAction:{label:"",classes:"",onAction:function(t){},reloadOnChange:!0},ajaxHeader:{title:"",value:""},language:{sortTitle:"Sort",moreActionTitle:"More actions",filtersTitle:"Filters",moreFiltersTitle:"More filters",selectedTitle:"selected",resultsTitle:"result",noResultsFoundTitle:"No _RESOURCE_ found"},button:{label:"Button",action:function(){},style:"default",classList:[],id:""},logModes:["console","throw","alert"],idPre:"JsDataTable-",moreFilterPre:"jsd-more-filters-"};var X={TABLE_DRAW_START:"table:draw.start",TABLE_DRAW_FINISH:"table:draw.finish",SELECTION:"table:selection"},W={container:"E0991-Datatable",tabContainer:"E0991-tab-container",tabLink:"E0991-tab-link",tabLabel:"E0991-tab-label",filtersContainer:"E0991-filter-container",queryValueContainer:"E0991-query-value-container",queryValueInput:"E0991-query-input",button:"E0991-button-clear",btn:"E0991-BTN",btnAction:"E0991-BTN-action",inpt:"E0991-INPT",sel:"E0991-SEL",label:"E0991-Label",textIpt:"E0991-INPT-TEXT",checkboxIpt:"E0991-INPT-CHECK",radioIpt:"E0991-INPT-RADIO",textareaIpt:"E0991-INPT-TEXTAREA",svg:"E0991-SVG",queryIconContainer:"E0991-query-icon-container",dropdownControl:"E0991-Dropdown",dropdownControlButton:"E0991-Dropdown-Control",dropdown:"E0991-Dropdown-Menu",filterControlContainer:"E0991-filter-control-container",filterControlGroup:"E0991-filter-control-group",filterControlItem:"E0991-filter-control-item",filterControlButton:"E0991-filter-control-button",moreFilterButtonOpen:"E0991-more-filter-button",sortControlItem:"E0991-sort-control-item",iconButton:"E0991-icon-button",dropdownMenuItem:"E0991-Dropdown-Menu-Item",dropdownMenuButton:"E0991-Dropdown-Menu-Button",buttonLink:"E0991-Button-Link",dropdownOpen:"E0991-Dropdown-Open",filterItem:"E0991-Filter-Item",moreFilterItem:"E0991-More-Filter-Item",moreActionLink:"E0991-More-Action-Link",textPrimary:"E0991-Text-Primary",textError:"E0991-Text-Error",textDefault:"E0991-Text-Default",activeFilterTable:"E0991-Active-Filter-Table",activeBulkActions:"E0991-Active",hidden:"E0991-Hidden",moreFiltersContainer:"E0991-More-Filter-Container",moreFiltersModal:"E0991-More-Filter-Modal",moreFiltersModalContent:"E0991-More-Filter-Modal-Content",moreFiltersModalCard:"E0991-More-Filter-Modal-Card",moreFiltersModalHeader:"E0991-More-Filter-Modal-Header",closeMoreFiltersButton:"E0991-More-Filter-Modal-Close",moreFiltersList:"E0991-More-Filter-List",moreFilterItemTitle:"E0991-More-Filter-Item-Title",clearFilterButton:"E0991-Filter-Clear",moreFilters:"E0991-More-Filters",moreFilterMenuItem:"E0991-More-Filters-Item",tableContainer:"E0991-Table-Container",skeletonLine:"E0991-Skeleton-Line",skeletonLines:"E0991-Skeleton-Lines",selectAllContainer:"E0991-Select-All-Rows",selectAllToggler:"E0991-Select-All-Toggler",selectAllWrapper:"E0991-Select-All-Wrapper",selectAllGroupButton:"E0991-Select-All-Group-Button",selectAllLabel:"E0991-Select-All-Label",noResults:"E0991-No-Results",paginationContainer:"E0991-Pagination-Container",paginationResultInfo:"E0991-Pagination-Result-Info",paginationNav:"E0991-Pagination-Nav",paginationControl:"E0991-Pagination-Control",paginationPageSelectControl:"E0991-Pagination-Page-Control",paginationLabel:"E0991-Pagination-Label",rowSelected:"E0991-Row-Selected",filterTableItem:"E0991-Filter-Table-Item",filterRemoveButton:"E0991-Filter-Table-Item-Remove-Button"};t.fn.dataTable=V,V.e=t,t.fn.dataTableSettings=V.settings,t.fn.dataTableExt=V.ext,t.fn.JsDataTable=function(e){return t(this).dataTable(e).api()},t.each(V,function(e,n){t.fn.JsDataTable[e]=n}),t.fn.dataTable}(jQuery);