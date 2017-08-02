// src/routes.js
import React from 'react';
import { Route, Switch } from 'react-router-dom';

import App from './components/App';
import NotFound from './components/NotFound';

const Routes = (props) => (
    <div>
        <Switch>
            <Route path="/" exact component={App} />
            <Route path="*" component={NotFound} />
        </Switch>
    </div>
);

export default Routes;