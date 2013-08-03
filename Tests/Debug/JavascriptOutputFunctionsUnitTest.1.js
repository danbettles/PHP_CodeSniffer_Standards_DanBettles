alert('here');

//At most, we'll need to look six tokens behind, so we can be sure that `alert` belongs only to `window`, if anything.  
//We'll need to look ahead by two tokens to make sure we're looking at a function call.

alert('here');
window.alert('here');

var oOutput = {
    alert: function (message) {
        window.alert(message);
    },
    window: {
        alert: function (message) {
            window.alert(message);
        }
    }
};

oOutput.alert('here');
window . alert ('here');
oOutput . alert ('here');
 alert ('here');
oOutput . window . alert ('here');

var alert = 'foo';

if (true) {
    alert('here');
}

alert('here');

if (true) {
    window.alert('here');
}

window.alert('here');

alert ('here');
  alert ('here');