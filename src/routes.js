// src/routes.js
import React from 'react';
import { Route,Link } from 'react-router-dom';

import App from './components/App';
import NotFound from './components/NotFound';

const Routes = (props) => (
    <div>
        <Route path="/" component={App} />
        <Route path="*" component={NotFound} />
    </div>
);

export default Routes;