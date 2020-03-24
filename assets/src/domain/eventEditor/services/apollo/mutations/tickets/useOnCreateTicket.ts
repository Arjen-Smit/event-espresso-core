import { pathOr } from 'ramda';

import updatePriceCache from './updatePriceCache';
import useUpdateTicketCache from './useUpdateTicketCache';
import { Ticket, Price } from '@edtrServices/apollo/types';
import { TicketMutationCallbackFn, TicketMutationCallbackFnArgs } from '../types';
import { useRelations } from '@appServices/apollo/relations';

const useOnCreateTicket = (): TicketMutationCallbackFn => {
	const { updateRelations, addRelation } = useRelations();

	const updateTicketCache = useUpdateTicketCache();

	const onCreateTicket = ({ proxy, datetimeIds, ticket, tickets, prices }: TicketMutationCallbackFnArgs): void => {
		if (ticket.id) {
			const { nodes = [] } = tickets;
			const ticketIn = nodes.map(({ id }: Ticket) => id);
			const { id: ticketId } = ticket;

			// Update prices cache for the changed tickets,
			// to avoid refetching of prices.
			updatePriceCache({ proxy, prices, ticketIn, ticketId, action: 'add' });

			// Set relations with datetimes
			updateRelations({
				entity: 'tickets',
				entityId: ticketId,
				relation: 'datetimes',
				relationIds: datetimeIds,
			});
			datetimeIds.forEach((entityId: string) => {
				addRelation({
					entity: 'datetimes',
					entityId,
					relation: 'tickets',
					relationId: ticketId,
				});
			});

			// Set relations with prices
			const priceIds = pathOr<Price[]>([], ['nodes'], prices).map(({ id }: Price) => id);
			updateRelations({
				entity: 'tickets',
				entityId: ticketId,
				relation: 'prices',
				relationIds: priceIds,
			});
			priceIds.forEach((entityId: string) => {
				addRelation({
					entity: 'prices',
					entityId,
					relation: 'tickets',
					relationId: ticketId,
				});
			});
		}
		// Update ticket cache after price cache is updated.
		updateTicketCache({ proxy, tickets, ticket, action: 'add' });
	};

	return onCreateTicket;
};

export default useOnCreateTicket;
