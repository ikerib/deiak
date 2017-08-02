import React, {  Component } from 'react';

import './style.css';

export default class NotFound extends Component {

    render() {
        const { className, ...props } = this.props;
        return (
            <div className="danger" {...props}>
                <h1>
                    404 <small>Not Found :(</small>
                </h1>
            </div>
        );
    }
}