
// public/js/my-react-component.js

function ManageSubscriptionBenefits() {
    return React.createElement(
        'div',
        null,
        React.createElement('h1', null, 'Hello from React!')
    );
}

// Render React component in the specified container
const container = document.getElementById('react-container');
ReactDOM.render(React.createElement(ManageSubscriptionBenefits), container);
