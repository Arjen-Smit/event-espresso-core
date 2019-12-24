/**
 * External imports
 */
import { createContext } from 'react';
import useRelationsManager from '../apollo/relations/useRelationsManager';
import { RelationsManager } from '../apollo/relations';

const RelationsContext = createContext<RelationsManager | null>(null);

const { Provider } = RelationsContext;

const RelationsProvider = ({ children }) => {
	const relations = useRelationsManager();
	return <Provider value={relations}>{children}</Provider>;
};

export { RelationsContext, RelationsProvider };
