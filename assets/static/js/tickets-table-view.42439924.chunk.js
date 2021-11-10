(this.webpackJsonproot=this.webpackJsonproot||[]).push([[26],{1777:function(e,t,n){"use strict";n.d(t,"a",(function(){return r}));var i=n(3),c=n(5),a=n(15),s=n(1),r=function(e){var t=e.ticket,n=Object(a.useRegistrationsLink)({ticket_id:t.dbId}),r=Object(i.__)("total registrations."),l=Object(i.__)("view ALL registrations for this ticket.");return Object(s.jsx)(c.ItemCount,{count:t.registrationCount,emphasizeZero:!1,title:r,children:Object(s.jsx)(c.RegistrationsLink,{href:n,tooltip:l})})}},1778:function(e,t,n){"use strict";var i=n(44),c=n(37),a=n.n(c),s=n(0),r=n(3),l=n(16),u=n(5),o=n(15),b=n(163),j=n(1);t.a=function(e){var t=e.entity,n=Object(o.useTicketMutator)(t.id).updateEntity,c=Object(b.useSystemNotifications)(),O=Object(o.useCappedQuantity)(),d=Object(s.useCallback)(function(){var e=Object(i.a)(a.a.mark((function e(i){var s,u,o,b;return a.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(s=Object(l.parseInfinity)(i),u=O({quantity:s,ticketId:t.id}),o=u!==s,b=u!==t.quantity,o&&c.info({message:Object(r.__)("Ticket quantity has been adjusted because it cannot be more than the related event date capacity.")}),!b&&!o){e.next=8;break}return e.next=8,n({quantity:u});case 8:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),[O,t.id,t.quantity,c,n]);return Object(j.jsx)(u.InlineEditInfinity,{"data-testid":"ee-ticket-inline-qty",onChange:d,tooltip:Object(r.__)("edit quantity of tickets available\u2026"),value:"".concat(t.quantity)},t.cacheId)}},1779:function(e,t,n){"use strict";n.d(t,"a",(function(){return l})),n.d(t,"b",(function(){return _}));var i=n(0),c=n(3),a=n(15),s=n(5),r=n(1),l=function(e){var t=e.className,n=e["data-testid"],l=e.entity,u=e.view,o=void 0===u?"card":u,b=Object(a.useTicketMutator)(l.id).updateEntity,j="card"===o&&2,O=Object(i.useCallback)((function(e){e!==l.name&&b({name:e})}),[l.name,b]);return Object(r.jsx)(s.InlineEditText,{className:t,"data-testid":n,lineCount:j,onChange:O,tag:"table"===o?"div":"h4",tooltip:Object(c.__)("click to edit title\u2026"),value:l.name||Object(c.__)("add title\u2026")})},u=n(29),o=n(63),b=n(26),j=n(24),O=n(12),d=n(84),p=n(32),f=n(2),m=n(44),k=n(37),y=n.n(k),v=function(e){var t=Object(a.useLazyTicket)(),n=Object(a.useTicketPrices)(),c=Object(d.useInitialState)({ticketId:e,getTicket:t,getTicketPrices:n}),s=Object(d.useDefaultBasePrice)(),r=Object(d.useMutatePrices)(),l=Object(a.useTicketMutator)(e).updateEntity;return Object(i.useCallback)(function(){var e=Object(m.a)(y.a.mark((function e(t){var n,i,a,u,o,b,j,m,k;return y.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return i=c(null),a=Object(f.a)(Object(f.a)({},i.ticket),{},{price:t}),i=Object(f.a)(Object(f.a)({},i),{},{ticket:a}),u=Object(O.getBasePrice)(i.prices),o=Object(O.getPriceModifiers)(i.prices),b=Object(d.calculateBasePrice)(null===(n=i.ticket)||void 0===n?void 0:n.price,i.prices),j=u?Object(f.a)(Object(f.a)({},u),{},{isModified:!0}):Object(f.a)(Object(f.a)({},s),{},{order:1,isNew:!0}),m=[Object(f.a)(Object(f.a)({},j),{},{amount:b})].concat(Object(p.a)(o)),e.next=10,r(m);case 10:return k=e.sent,e.next=13,l({price:t,reverseCalculate:!0,prices:k});case 13:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),[s,c,r,l])},_=function(e){var t=e.entity,n=e.className,a=Object(j.useMoneyDisplay)(),l=a.afterAmount,p=a.beforeAmount,f=a.formatAmount,m=v(t.id),k=Object(i.useCallback)((function(e){var n=e.amount,i=Math.abs(n);i!==t.price&&m(i)}),[m,t.price]),y=Object(d.useLockedTicketAction)(t,"COPY/TRASH"),_=y.alertContainer,h=y.showAlert,x=Object(b.useMemoStringify)({className:n}),C=Object(O.isLocked)(t),T=Object(c.__)("edit ticket total\u2026");return C?Object(r.jsxs)(u.Clickable,{as:"div",className:"ee-ticket-editable-price",onClick:h,children:[Object(r.jsx)(o.CurrencyDisplay,{className:n,value:t.price}),_]}):Object(r.jsx)(s.InlineEditCurrency,{afterAmount:l,amount:t.price,beforeAmount:p,formatAmount:f,id:t.id,placeholder:Object(c.__)("set price\u2026"),wrapperProps:x,onChange:k,tag:"h3",tooltip:T})}},1782:function(e,t,n){"use strict";var i=n(2),c=n(8),a=n(5),s=n(453),r=function(e){return Object(s.b)("ticket",e)},l=n(1);t.a=function(e){var t=e.entity,n=Object(c.a)(e,["entity"]),s=r(t);return Object(l.jsx)(a.EntityActionsMenu,Object(i.a)(Object(i.a)({},n),{},{menuItems:s}))}},1783:function(e,t,n){"use strict";n.r(t);var i=n(3),c=n(63),a=n(0),s=n(15),r=n(2),l=n(9),u=n(24),o=n(1),b=Object(u.withFeature)("use_bulk_edit")((function(e){var t=Object(s.useVisibleTicketIds)(),n=Object(l.a)(t,1)[0];return Object(o.jsx)(c.ActionCheckbox,Object(r.a)(Object(r.a)({},e),{},{visibleEntityIds:n}))})),j=function(){var e=Object(a.useMemo)((function(){return{className:"ee-entity-list-status-stripe",key:"stripe",size:"nano",textAlign:"center",value:""}}),[]),t=Object(a.useMemo)((function(){return{key:"id",size:"micro",textAlign:"end",value:Object(i.__)("ID")}}),[]),n=Object(a.useMemo)((function(){return{key:"name",size:"huge",value:Object(i.__)("Name")}}),[]),c=Object(a.useMemo)((function(){return{key:"start",size:"default",value:Object(o.jsxs)(o.Fragment,{children:[Object(o.jsx)("span",{className:"ee-rspnsv-table-long-label",children:Object(i.__)("Goes on Sale")}),Object(o.jsx)("span",{className:"ee-rspnsv-table-short-label",children:Object(i.__)("On Sale")})]})}}),[]),r=Object(a.useMemo)((function(){return{key:"end",size:"default",value:Object(o.jsxs)(o.Fragment,{children:[Object(o.jsx)("span",{className:"ee-rspnsv-table-long-label",children:Object(i.__)("Sale Ends")}),Object(o.jsx)("span",{className:"ee-rspnsv-table-short-label",children:Object(i.__)("Ends")})]})}}),[]),l=Object(a.useMemo)((function(){return{key:"price",size:"tiny",textAlign:"end",value:Object(i.__)("Price")}}),[]),u=Object(a.useMemo)((function(){return{key:"quantity",size:"tiny",textAlign:"end",value:Object(i.__)("Quantity")}}),[]),j=Object(a.useMemo)((function(){return{key:"sold",size:"tiny",textAlign:"end",value:Object(i.__)("Sold")}}),[]),O=Object(a.useMemo)((function(){return{key:"registrations",size:"smaller",textAlign:"center",value:Object(o.jsxs)(o.Fragment,{children:[Object(o.jsx)("span",{className:"ee-rspnsv-table-long-label",children:Object(i.__)("Registrations")}),Object(o.jsx)("span",{className:"ee-rspnsv-table-short-label",children:Object(i.__)("Regs")})]})}}),[]),d=Object(a.useMemo)((function(){return{key:"actions",size:"big",textAlign:"center",value:Object(o.jsxs)(o.Fragment,{children:[Object(o.jsx)("span",{className:"ee-rspnsv-table-long-label",children:Object(i.__)("Actions")}),Object(o.jsx)("span",{className:"ee-rspnsv-table-short-label",children:Object(i.__)("Actions")})]})}}),[]);return Object(a.useCallback)((function(i){var a=i.displayStartOrEndDate,p=i.showBulkActions;return{cells:[e,p&&{key:"checkbox",size:"micro",textAlign:"center",value:Object(o.jsx)("div",{className:"ee-rspnsv-table-hide-on-mobile",children:Object(o.jsx)(b,{})})},t,n,c,r,l,u,j,O,d].filter(Boolean).filter(Object(s.filterCellByStartOrEndDate)(a)),className:"ee-editor-ticket-list-items-header-row",key:"ticket-header-row",primary:!0,type:"row"}}),[t,d,r,n,l,u,O,j,c,e])},O=n(10),d=n.n(O),p=n(111),f=n(4),m=n(5),k=n(35),y=n(322),v=n(12),_=n(1782),h=n(1778),x=n(1779),C=n(1777),T=function(){var e=Object(s.useTickets)(),t=Object(a.useCallback)((function(t){return Object(v.findEntityByGuid)(e)(t)}),[e]),n=Object(s.useLazyTicket)(),i=Object(u.useTimeZoneTime)().formatForSite;return Object(a.useCallback)((function(e){var a=e.entityId,r=e.filterState,l=t(a)||n(a),u=r.displayStartOrEndDate,j=r.showBulkActions,O=Object(y.getTicketBackgroundColorClassName)(l),v=l.dbId||0,T=Object(y.ticketStatus)(l),g={className:d()("ee-entity-list-status-stripe",O),key:"stripe",showValueOnMobile:!0,textAlign:"center",value:l.name},w=j&&{key:"cell",size:"micro",textAlign:"center",value:Object(o.jsx)(b,{dbId:l.dbId,id:l.id})},A={key:"id",size:"micro",textAlign:"end",value:v},S={className:"ee-col-name ee-rspnsv-table-hide-on-mobile",key:"name",size:"huge",value:Object(o.jsx)(x.a,{className:"ee-entity-name ee-entity-list-text ee-focus-priority-5","data-testid":"ee-entity-list-view-row-editable-".concat(l.dbId),entity:l,view:"table"})},E={key:"start",size:"default",value:i(Object(p.a)(l.startDate),k.ENTITY_LIST_DATE_TIME_FORMAT)},I={key:"end",size:"default",value:i(Object(p.a)(l.endDate),k.ENTITY_LIST_DATE_TIME_FORMAT)},M={key:"price",size:"tiny",textAlign:"end",value:Object(o.jsx)(c.CurrencyDisplay,{value:l.price})},B={key:"sold",size:"tiny",textAlign:"end",value:l.sold},N=[g,w,A,S,E,I,M,{className:"ee-col__inline-edit",key:"quantity",size:"tiny",textAlign:"end",value:Object(o.jsx)(h.a,{entity:l})},B,{key:"registrations",size:"smaller",textAlign:"center",value:Object(o.jsx)(C.a,{ticket:l})},{key:"actions",size:"big",textAlign:"center",value:Object(o.jsx)(_.a,{entity:l})}].filter(Boolean);return{cells:f.pipe(f.filter(Object(s.filterCellByStartOrEndDate)(u)),Object(m.addZebraStripesOnMobile)(["row","stripe","name","actions"]))(N),className:"ee-editor-ticket-list-view-row ".concat(T),"data-testid":"ee-ticket-list-view-row-".concat(l.dbId),id:"ee-editor-ticket-list-view-row-".concat(l.id),key:"row-".concat(l.id),rowClassName:"ee-entity-list-item",type:"row"}}),[i,n,t])},g=n(26),w=n(84),A=n(1762),S=n(1666),E=n(1763),I=n(50),M=n(44),B=n(37),N=n.n(B),D=n(217),P=n(82),z=function(){var e=Object(M.a)(N.a.mark((function e(t){return N.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Object(P.yupToFinalFormErrors)(R,t);case 2:return e.abrupt("return",e.sent);case 3:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),R=D.b({name:D.c().min(3,(function(){return Object(i.__)("Name must be at least three characters")}))}),F=Object(I.intervalsToOptions)(f.pick(["months","weeks","days","hours","minutes"],I.DATE_INTERVALS),!0),q=function(e){var t=e.onSubmit,n=Object(a.useCallback)((function(e,n){for(var i=arguments.length,c=new Array(i>2?i-2:0),a=2;a<i;a++)c[a-2]=arguments[a];return t.apply(void 0,[e,n].concat(c))}),[t]),c=Object(g.useMemoStringify)({className:"ee-form-item-pair"});return Object(a.useMemo)((function(){return Object(r.a)(Object(r.a)({},e),{},{onSubmit:n,validate:z,layout:"horizontal",debugFields:["values","errors"],sections:[{name:"basics",icon:A.a,title:Object(i.__)("Basics"),fields:[{name:"name",label:Object(i.__)("Name"),fieldType:"text",min:3},{name:"description",label:Object(i.__)("Description"),fieldType:"simple-text-editor"}]},{name:"dates",icon:S.a,title:Object(i.__)("Dates"),fields:[{name:"shiftDates",label:Object(i.__)("Shift dates"),fieldType:"group",formControlProps:{className:"shift-dates"},subFields:[{name:"value",fieldType:"number",min:1},{name:"unit",fieldType:"select",options:F},{name:"type",fieldType:"select",options:[{label:"",value:""},{label:Object(i.__)("earlier"),value:"earlier"},{label:Object(i.__)("later"),value:"later"}]}]}]},{name:"details",icon:E.a,title:Object(i.__)("Details"),fields:[{name:"quantity",label:Object(i.__)("Quantity For Sale"),fieldType:"number",formControlProps:c,parseAsInfinity:!0,max:1e6,min:-1},{name:"uses",label:Object(i.__)("Number of Uses"),fieldType:"number",parseAsInfinity:!0,formControlProps:c,min:0},{name:"min",label:Object(i.__)("Minimum Quantity"),fieldType:"number",formControlProps:c,max:1e6,min:0},{name:"max",label:Object(i.__)("Maximum Quantity"),fieldType:"number",parseAsInfinity:!0,formControlProps:c,max:1e6,min:-1},{name:"isRequired",label:Object(i.__)("Required Ticket"),fieldType:"switch",formControlProps:c}]}]})}),[c,e,n])},L=function(e){var t=Object(u.useBulkEdit)(),n=t.getSelected,i=t.unSelectAll,c=Object(s.useTickets)(),l=Object(s.useBulkEditTickets)().updateEntities,o=Object(s.useCappedQuantity)();return Object(a.useCallback)(function(){var t=Object(M.a)(N.a.mark((function t(a){var u;return N.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e(),"quantity"in(u=Object(s.formToBulkUpdateInput)(a,c,n())).sharedInput&&(u.uniqueInputs=u.uniqueInputs.map((function(e){var t=o({quantity:u.sharedInput.quantity,ticketId:e.id});return Object(r.a)(Object(r.a)({},e),{},{quantity:t})}))),i(),t.next=6,l(u);case 6:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}(),[c,o,n,e,i,l])},G=function(e){var t=e.onClose,n=e.isOpen,a=L(t),s=q({onSubmit:a});return Object(o.jsx)(c.BulkEditDetails,{formConfig:s,isOpen:n,onClose:t,title:Object(i.__)("Bulk edit ticket details"),warning:Object(i.__)("any changes will be applied to ALL of the selected tickets.")})},Q=function(e){var t=e.areTrashedTickets,n=e.onClose,i=Object(u.useBulkEdit)(),c=i.getSelected,r=i.unSelectAll,l=Object(s.useBulkDeleteTickets)();return Object(a.useCallback)((function(){n(),r(),l({entityIds:c(),deletePermanently:t})}),[t,l,c,n,r])},Y=function(e){var t=e.areTrashedTickets,n=e.onClose,c=Q({areTrashedTickets:t,onClose:n}),s=Object(m.useConfirmationDialog)({message:t?Object(i.__)("Are you sure you want to permanently delete these tickets? This action can NOT be undone!"):Object(i.__)("Are you sure you want to trash these tickets?"),title:t?Object(i.__)("Delete tickets permanently"):Object(i.__)("Trash tickets"),onConfirm:c,onCancel:n}),r=s.confirmationDialog,l=s.onOpen;return Object(a.useEffect)((function(){return l()}),[]),Object(o.jsx)(o.Fragment,{children:r})},V=n(29),Z=function(e){var t=e.setEditMode,n=Object(a.useCallback)((function(){return t("together")}),[t]),c=Object(a.useCallback)((function(){return t("separate")}),[t]);return Object(o.jsxs)(V.Box,{display:"flex",alignItems:"center",justifyContent:"center",height:"100%",children:[Object(o.jsxs)(V.Box,{children:[Object(o.jsx)(m.Button,{onClick:n,buttonText:Object(i.__)("Edit all prices together")}),Object(o.jsx)("p",{children:Object(i.__)("Edit all the selected ticket prices dynamically")})]}),Object(o.jsx)(m.Divider,{orientation:"vertical"}),Object(o.jsxs)(V.Box,{children:[Object(o.jsx)(m.Button,{onClick:c,buttonText:Object(i.__)("Edit prices individually")}),Object(o.jsx)("p",{children:Object(i.__)("Edit prices for each ticket individually")})]})]})},J=function(e){var t=e.onSubmit,n=e.onReset,c=e.onCancel;return Object(o.jsxs)(m.ButtonRow,{fullWidth:!0,horizontalAlign:"right",topBordered:!0,children:[n&&Object(o.jsx)(m.Button,{buttonText:Object(i.__)("Reset"),onClick:n,type:"reset"}),c&&Object(o.jsx)(m.Button,{buttonText:Object(i.__)("Cancel"),onClick:c}),Object(o.jsx)(m.Button,{buttonText:Object(i.__)("Submit"),buttonType:m.ButtonType.PRIMARY,onClick:t,type:"submit"})]})},U=n(32),W=function(e){var t=Object(w.useDataState)(),n=t.prices,i=t.ticket,c=Object(u.useBulkEdit)().getSelected,l=Object(w.useMutateTicket)(),o=Object(s.useTicketPrices)(),b=Object(s.useBulkDeletePrices)();return Object(a.useCallback)(Object(M.a)(N.a.mark((function t(){var a,s;return N.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e(),a=c().reduce((function(e,t){return[].concat(Object(U.a)(e),Object(U.a)(o(t)))}),[]),s=a.filter(v.isNotDefault),t.next=5,b(Object(v.getGuids)(s));case 5:return t.next=7,Promise.all(c().map(function(){var e=Object(M.a)(N.a.mark((function e(t){return N.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,l(Object(r.a)(Object(r.a)({},i),{},{id:t,isModified:!0,prices:n}));case 2:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()));case 7:case"end":return t.stop()}}),t)}))),[b,c,o,l,e,n,i])},H=Object(w.withContext)((function(e){var t=e.onClose,n=Object(w.useAddDefaultPrices)();Object(a.useEffect)((function(){n()}),[]);var i=W(t);return Object(o.jsxs)(o.Fragment,{children:[Object(o.jsx)(w.TicketPriceCalculator,{}),Object(o.jsx)(J,{onSubmit:i,onReset:n})]})}),{ticketId:""}),K=function(e){var t=e.setTPCState,n=Object(w.useDataState)(),c=n.ticket,s=n.getData;return Object(a.useEffect)((function(){t(s())}),[s]),Object(o.jsxs)(o.Fragment,{children:[Object(o.jsx)("header",{children:Object(i.sprintf)(Object(i.__)("Edit prices for Ticket: %s"),c.name)}),Object(o.jsx)(w.TicketPriceCalculator,{})]})},X=n(14),$=function(e,t){var n=Object(w.useOnSubmitPrices)();return Object(a.useCallback)(Object(M.a)(N.a.mark((function i(){return N.a.wrap((function(i){for(;;)switch(i.prev=i.next){case 0:return e(),i.next=3,Promise.all(Object.values(t()).map(function(){var e=Object(M.a)(N.a.mark((function e(t){return N.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,n(t);case 2:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()));case 3:case"end":return i.stop()}}),i)}))),[t,e,n])},ee=function(e){var t=e.onClose,n=Object(u.useBulkEdit)().getSelected,i=function(){var e=Object(a.useRef)({}),t=Object(a.useCallback)((function(t){var n;e.current=Object(r.a)(Object(r.a)({},e.current),{},Object(X.a)({},null===t||void 0===t||null===(n=t.ticket)||void 0===n?void 0:n.id,t))}),[e]),n=Object(a.useCallback)((function(){return e.current}),[e]);return Object(a.useMemo)((function(){return{setTPCState:t,getDataStates:n}}),[t,n])}(),c=i.getDataStates,s=i.setTPCState,l=$(t,c),b=n();return Object(o.jsxs)(o.Fragment,{children:[b.map((function(e){var t=Object(w.withContext)(K,{ticketId:e});return Object(o.jsx)(t,{setTPCState:s},e)})),Object(o.jsx)(J,{onSubmit:l,onCancel:t})]})},te=function(e){var t=e.onClose,n=e.isOpen,c=Object(a.useState)(),s=Object(l.a)(c,2),r=s[0],u=s[1];return Object(o.jsxs)(m.EntityEditModal,{isOpen:n,onClose:t,closeOnOverlayClick:!0,title:Object(i.__)("Bulk edit ticket prices"),showAlertOnClose:!1,children:[!r&&Object(o.jsx)(Z,{setEditMode:u}),"together"===r&&Object(o.jsx)(H,{onClose:t}),"separate"===r&&Object(o.jsx)(ee,{onClose:t})]})},ne=Object(u.withFeature)("use_bulk_edit")((function(){var e=Object(a.useState)(""),t=Object(l.a)(e,2),n=t[0],r=t[1],j=Object(g.useDisclosure)(),O=j.isOpen,d=j.onOpen,p=j.onClose,f=Object(s.useTicketsListFilterState)(),k=f.status,y=f.showBulkActions,_=Object(u.useBulkEdit)().getSelected,h=Object(s.useTickets)(),x=Object(a.useMemo)((function(){return Object(v.entitiesWithGuIdInArray)(h,_()).some((function(e){return Boolean(e.sold)}))}),[h,_]),C=k===v.TicketsStatus.trashedOnly,T=Object(g.useMemoStringify)([{value:"",label:Object(i.__)("bulk actions")},{value:"edit-details",label:Object(i.__)("edit ticket details")},{value:"delete",label:C?Object(i.__)("delete tickets"):Object(i.__)("trash tickets")},{value:"edit-prices",label:Object(i.__)("edit ticket prices"),disabled:x}]),A=Object(a.useCallback)((function(e){r(e),d()}),[d]);return Object(o.jsxs)(m.Collapsible,{show:y,children:[Object(o.jsx)(c.BulkActions,{Checkbox:b,defaultAction:"",id:"ee-bulk-edit-tickets-actions",onApply:x?null:A,options:T}),O&&Object(o.jsxs)(o.Fragment,{children:["edit-details"===n&&Object(o.jsx)(G,{isOpen:!0,onClose:p}),"delete"===n&&Object(o.jsx)(Y,{areTrashedTickets:C,onClose:p}),"edit-prices"===n&&Object(o.jsx)(te,{isOpen:!0,onClose:p})]}),Object(o.jsx)(m.ErrorMessage,{message:x&&w.SOLD_TICKET_ERROR_MESSAGE,variant:"subtle"})]})}));t.default=Object(u.withBulkEdit)((function(){var e=Object(s.useTicketsListFilterState)(),t=Object(s.useFilteredTicketIds)(),n=Object(s.useReorderTickets)(t).sortResponder,a=T(),r=j();return Object(o.jsxs)(o.Fragment,{children:[Object(o.jsx)(ne,{}),Object(o.jsx)(c.EntityTable,{bodyRowGenerator:a,domain:s.domain,entityIds:t,filterState:e,headerRowGenerator:r,listId:s.ticketsList,onSort:n,tableCaption:Object(i.__)("Tickets"),tableId:"ticket-entities-table-view"})]})}))}}]);
//# sourceMappingURL=tickets-table-view.42439924.chunk.js.map