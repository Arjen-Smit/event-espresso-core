(this.webpackJsonproot=this.webpackJsonproot||[]).push([[6],{120:function(e,t,a){"use strict";var n=a(34),i=a(51),s=Object(n.a)(i.a);t.a=s},126:function(e,t,a){"use strict";a.d(t,"a",(function(){return i}));var n=a(43);function i(e,t){return function(){var a=arguments.length;if(0===a)return t();var i=arguments[a-1];return Object(n.a)(i)||"function"!==typeof i[e]?t.apply(this,arguments):i[e].apply(i,Array.prototype.slice.call(arguments,0,a-1))}}},1739:function(e,t,a){"use strict";var n=a(3),i=a(10),s=a(16),c=a(1);t.a=function(e){var t=e.datetime,a=Object(s.useRegistrationsLink)({datetime_id:t.dbId}),l=Object(n.__)("view ALL registrations for this date.");return Object(c.jsx)(i.RegistrationsLink,{href:a,tooltip:l})}},1740:function(e,t,a){"use strict";var n=a(0),i=a(3),s=a(23),c=a(10),l=a(16),r=a(1);t.a=function(e){var t=e.entity,a=Object(l.useDatetimeMutator)(t.id).updateEntity,o=Object(l.useUpdateRelatedTickets)(),u=Object(l.useTicketQuantityForCapacity)(),b=Object(n.useCallback)((function(e){var n=Object(s.parseInfinity)(e);if(n!==t.capacity){a({capacity:n});var i=u(n);o(t.id,i)}}),[t.capacity,t.id,a,u,o]);return Object(r.jsx)(c.InlineEditInfinity,{"data-testid":"ee-datetime-inline-cap",onChange:b,tooltip:Object(i.__)("edit capacity (registration limit)\u2026"),value:"".concat(t.capacity)})}},1744:function(e,t,a){"use strict";var n=a(2),i=a(7),s=a(10),c=a(817),l=function(e){return Object(c.a)("datetime",e)},r=a(1);t.a=function(e){var t=e.entity,a=Object(i.a)(e,["entity"]),c=l(t);return Object(r.jsx)(s.EntityActionsMenu,Object(n.a)(Object(n.a)({},a),{},{menuItems:c}))}},1745:function(e,t,a){"use strict";a.d(t,"a",(function(){return r}));var n=a(0),i=a(3),s=a(16),c=a(10),l=a(1),r=function(e){var t=e.className,a=e["data-testid"],r=e.entity,o=e.view,u=void 0===o?"card":o,b=Object(s.useDatetimeMutator)(r.id).updateEntity,d=Object(i.__)("edit title\u2026"),j=r.name||d,O="card"===u&&2,m=Object(n.useCallback)((function(e){e!==r.name&&b({name:e})}),[r.name,b]);return Object(l.jsx)(c.InlineEditText,{className:t,"data-testid":a,lineCount:O,onChange:m,tag:"table"===u?"div":"h4",tooltip:d,value:j})}},1748:function(e,t,a){"use strict";a.r(t);var n=a(3),i=a(74),s=a(16),c=a(28),l=a(0),r=a(13),o=a.n(r),u=a(148),b=a(111),d=a(337),j=a(10),O=a(36),m=a(357),p=a(15),f=a(1739),y=a(1744),v=a(1740),_=a(1745),h=a(2),k=a(9),g=a(1),x=Object(c.withFeature)("use_bulk_edit")((function(e){var t=Object(s.useVisibleDatetimeIds)(),a=Object(k.a)(t,1)[0];return Object(g.jsx)(i.ActionCheckbox,Object(h.a)(Object(h.a)({},e),{},{visibleEntityIds:a}))})),A=Object(j.addZebraStripesOnMobile)(["row","stripe","name","actions"]),D=function(){var e=Object(s.useDatetimes)(),t=Object(l.useCallback)((function(t){return Object(p.findEntityByGuid)(e)(t)}),[e]),a=Object(s.useLazyDatetime)(),n=Object(c.useTimeZoneTime)().formatForSite;return Object(l.useCallback)((function(e){var i=e.entityId,c=e.filterState,l=t(i)||a(i),r=c.displayStartOrEndDate,j=c.showBulkActions,p=Object(m.getDatetimeBackgroundColorClassName)(l),h=l.dbId||0,k=Object(m.datetimeStatus)(l),D=[{className:o()("ee-entity-list-status-stripe",p),key:"stripe",showValueOnMobile:!0,textAlign:"center",value:l.name},j&&{key:"cell",size:"micro",textAlign:"center",value:Object(g.jsx)(x,{dbId:l.dbId,id:l.id})},{key:"id",size:"micro",textAlign:"end",value:h},{className:"ee-col-name ee-rspnsv-table-hide-on-mobile",key:"name",size:"huge",value:Object(g.jsx)(_.a,{className:"ee-entity-name ee-entity-list-text ee-focus-priority-5","data-testid":"ee-entity-list-view-row-editable-".concat(l.dbId),entity:l,view:"table"})},{key:"start",size:"default",value:n(Object(u.default)(l.startDate),O.ENTITY_LIST_DATE_TIME_FORMAT)},{key:"end",size:"default",value:n(Object(u.default)(l.endDate),O.ENTITY_LIST_DATE_TIME_FORMAT)},{className:"ee-col__inline-edit",key:"capacity",size:"tiny",textAlign:"end",value:Object(g.jsx)(v.a,{entity:l})},{key:"sold",size:"tiny",textAlign:"end",value:l.sold||0},{key:"registrations",size:"smaller",textAlign:"center",value:Object(g.jsx)(f.a,{datetime:l})},{key:"actions",size:"big",textAlign:"center",value:Object(g.jsx)(y.a,{entity:l})}].filter(Boolean),C=Object(b.a)(Object(s.filterCellByStartOrEndDate)(r));return{cells:Object(d.a)(C,A)(D),className:k,"data-testid":"ee-datetime-list-view-row-".concat(l.dbId),id:"ee-editor-datetime-list-view-row-".concat(l.id),key:"row-".concat(l.id),rowClassName:"ee-entity-list-item",type:"row"}}),[n,t,a])},C=function(){var e=Object(l.useMemo)((function(){return{className:"ee-entity-list-status-stripe",key:"stripe",size:"nano",textAlign:"center",value:""}}),[]),t=Object(l.useMemo)((function(){return{key:"id",size:"micro",textAlign:"end",value:Object(n.__)("ID")}}),[]),a=Object(l.useMemo)((function(){return{key:"name",size:"huge",value:Object(n.__)("Name")}}),[]),i=Object(l.useMemo)((function(){return{key:"start",size:"default",value:Object(g.jsxs)(g.Fragment,{children:[Object(g.jsx)("span",{className:"ee-rspnsv-table-long-label",children:Object(n.__)("Start Date")}),Object(g.jsx)("span",{className:"ee-rspnsv-table-short-label",children:Object(n.__)("Start")})]})}}),[]),c=Object(l.useMemo)((function(){return{key:"end",size:"default",value:Object(g.jsxs)(g.Fragment,{children:[Object(g.jsx)("span",{className:"ee-rspnsv-table-long-label",children:Object(n.__)("End Date")}),Object(g.jsx)("span",{className:"ee-rspnsv-table-short-label",children:Object(n.__)("End")})]})}}),[]),r=Object(l.useMemo)((function(){return{className:"ee-col__inline-edit",key:"capacity",size:"tiny",textAlign:"end",value:Object(g.jsxs)(g.Fragment,{children:[Object(g.jsx)("span",{className:"ee-rspnsv-table-long-label",children:Object(n.__)("Capacity")}),Object(g.jsx)("span",{className:"ee-rspnsv-table-short-label",children:Object(n.__)("Cap")})]})}}),[]),o=Object(l.useMemo)((function(){return{key:"sold",size:"tiny",textAlign:"end",value:Object(n.__)("Sold")}}),[]),u=Object(l.useMemo)((function(){return{key:"registrations",size:"smaller",textAlign:"center",value:Object(g.jsxs)(g.Fragment,{children:[Object(g.jsx)("span",{className:"ee-rspnsv-table-long-label",children:Object(n.__)("Reg list")}),Object(g.jsx)("span",{className:"ee-rspnsv-table-short-label",children:Object(n.__)("Regs")})]})}}),[]),b=Object(l.useMemo)((function(){return{key:"actions",size:"big",textAlign:"center",value:Object(g.jsxs)(g.Fragment,{children:[Object(g.jsx)("span",{className:"ee-rspnsv-table-long-label",children:Object(n.__)("Actions")}),Object(g.jsx)("span",{className:"ee-rspnsv-table-short-label",children:Object(n.__)("Actions")})]})}}),[]);return Object(l.useCallback)((function(n){var l=n.displayStartOrEndDate,d=n.showBulkActions;return{cells:[e,d&&{key:"checkbox",size:"micro",textAlign:"center",value:Object(g.jsx)("div",{className:"ee-rspnsv-table-hide-on-mobile",children:Object(g.jsx)(x,{})})},t,a,i,c,r,o,u,b].filter(Boolean).filter(Object(s.filterCellByStartOrEndDate)(l)),className:"ee-editor-date-list-items-header-row",key:"dates-list-header",primary:!0,type:"row"}}),[b,r,c,t,a,u,o,i,e])},w=a(30),E=a(514),T=a(1732),N=a(1656),S=a(1733),I=a(61),F=a(64),z=a(48),M=a.n(z),B=a(257),L=a(100),R=function(){var e=Object(F.a)(M.a.mark((function e(t){return M.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Object(L.yupToFinalFormErrors)(G,t);case 2:return e.abrupt("return",e.sent);case 3:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),G=B.b({name:B.c().min(3,(function(){return Object(n.__)("Name must be at least three characters")}))}),V=Object(I.intervalsToOptions)(Object(E.a)(["months","weeks","days","hours","minutes"],I.DATE_INTERVALS),!0),J=function(e){var t=e.onSubmit,a=Object(l.useCallback)((function(e,a){for(var n=arguments.length,i=new Array(n>2?n-2:0),s=2;s<n;s++)i[s-2]=arguments[s];return t.apply(void 0,[e,a].concat(i))}),[t]),i=Object(w.useMemoStringify)({className:"ee-form-item-pair"});return Object(l.useMemo)((function(){return Object(h.a)(Object(h.a)({},e),{},{onSubmit:a,validate:R,layout:"horizontal",debugFields:["values","errors"],sections:[{name:"basics",icon:T.a,title:Object(n.__)("Basics"),fields:[{name:"name",label:Object(n.__)("Name"),fieldType:"text",min:3},{name:"description",label:Object(n.__)("Description"),fieldType:"simple-text-editor"}]},{name:"dates",icon:N.a,title:Object(n.__)("Dates"),fields:[{name:"shiftDates",label:Object(n.__)("Shift dates"),fieldType:"group",formControlProps:{className:"shift-dates"},subFields:[{name:"value",fieldType:"number",min:1},{name:"unit",fieldType:"select",options:V},{name:"type",fieldType:"select",options:[{label:"",value:""},{label:Object(n.__)("earlier"),value:"earlier"},{label:Object(n.__)("later"),value:"later"}]}]}]},{name:"details",icon:S.a,title:Object(n.__)("Details"),fields:[{name:"capacity",label:Object(n.__)("Capacity"),fieldType:"number",parseAsInfinity:!0,min:-1,formControlProps:i}]}]})}),[i,e,a])},P=function(e){var t=Object(c.useBulkEdit)(),a=t.getSelected,n=t.unSelectAll,i=Object(s.useDatetimes)(),r=Object(s.useBulkEditDatetimes)().updateEntities;return Object(l.useCallback)((function(t){e(),n();var c=Object(s.formToBulkUpdateInput)(t,i,a());r(c)}),[i,a,e,n,r])},U=function(e){var t=e.onClose,a=e.isOpen,s=P(t),c=J({onSubmit:s});return Object(g.jsx)(i.BulkEditDetails,{formConfig:c,isOpen:a,onClose:t,title:Object(n.__)("Bulk edit date details"),warning:Object(n.__)("any changes will be applied to ALL of the selected dates.")})},Y=function(e){var t=e.areTrashedDates,a=e.onClose,n=Object(c.useBulkEdit)(),i=n.getSelected,r=n.unSelectAll,o=Object(s.useBulkDeleteDatetimes)();return Object(l.useCallback)((function(){a(),r(),o(i(),t)}),[t,o,i,a,r])},Z=function(e){var t=e.areTrashedDates,a=e.onClose,i=Y({areTrashedDates:t,onClose:a}),s=Object(j.useConfirmationDialog)({message:t?Object(n.__)("Are you sure you want to permanently delete these datetimes? This action can NOT be undone!"):Object(n.__)("Are you sure you want to trash these datetimes?"),title:t?Object(n.__)("Delete datetimes permanently"):Object(n.__)("Trash datetimes"),onConfirm:i,onCancel:a}),c=s.confirmationDialog,r=s.onOpen;return Object(l.useEffect)((function(){return r()}),[]),Object(g.jsx)(g.Fragment,{children:c})},q=["edit-details","delete",""],Q=Object(c.withFeature)("use_bulk_edit")((function(){var e=Object(l.useState)(""),t=Object(k.a)(e,2),a=t[0],r=t[1],o=Object(c.useBulkEdit)(),u=Object(w.useDisclosure)(),b=u.isOpen,d=u.onOpen,O=u.onClose,m=Object(s.useDatesListFilterState)(),f=m.status,y=m.showBulkActions,v=f===p.DatetimeStatus.trashedOnly,_=Object(w.useMemoStringify)(s.hooks.applyFilters("eventEditor.datetimes.bulkEdit.actions",[{value:"",label:Object(n.__)("bulk actions")},{value:"edit-details",label:Object(n.__)("edit datetime details")},{value:"delete",label:v?Object(n.__)("delete datetimes"):Object(n.__)("trash datetimes")}])),h=Object(l.useCallback)((function(e){r(e),q.includes(e)&&d(),s.hooks.doAction("eventEditor.datetimes.bulkEdit.apply",e,o)}),[o,d]);return Object(g.jsxs)(j.Collapsible,{show:y,children:[Object(g.jsx)(i.BulkActions,{Checkbox:x,defaultAction:"",id:"ee-bulk-edit-dates-actions",onApply:h,options:_}),b&&Object(g.jsxs)(g.Fragment,{children:["edit-details"===a&&Object(g.jsx)(U,{isOpen:!0,onClose:O}),"delete"===a&&Object(g.jsx)(Z,{areTrashedDates:v,onClose:O})]})]})}));t.default=Object(c.withBulkEdit)((function(){var e=Object(s.useDatesListFilterState)(),t=Object(s.useFilteredDateIds)(),a=D(),c=C();return Object(g.jsxs)(g.Fragment,{children:[Object(g.jsx)(Q,{}),Object(g.jsx)(i.EntityTable,{bodyRowGenerator:a,domain:s.domain,entityIds:t,filterState:e,headerRowGenerator:c,listId:s.datesList,tableCaption:Object(n.__)("Event Dates"),tableId:"date-entities-table-view"})]})}))},209:function(e,t,a){"use strict";var n=a(126),i=a(34),s=Object(i.a)(Object(n.a)("slice",(function(e,t,a){return Array.prototype.slice.call(a,e,t)})));t.a=s},337:function(e,t,a){"use strict";a.d(t,"a",(function(){return u}));var n=a(50);function i(e,t){return function(){return t.call(this,e.apply(this,arguments))}}var s=a(120),c=a(126),l=a(11),r=a(209),o=Object(l.a)(Object(c.a)("tail",Object(r.a)(1,1/0)));function u(){if(0===arguments.length)throw new Error("pipe requires at least one argument");return Object(n.a)(arguments[0].length,Object(s.a)(i,arguments[0],o(arguments)))}}}]);
//# sourceMappingURL=dates-table-view.72885bde.chunk.js.map