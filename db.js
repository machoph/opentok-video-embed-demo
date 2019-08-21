// This is our simple DB in memory. A real-world use case would use an actual database.
var config  = require('./conf');
let DB = {

  embed_code: `<div id="otEmbedContainer" style="width:100%; height:100%"></div>
                    <script src="https://tokbox.com/embed/embed/ot-embed.js?embedId=${config.get("OPENTOK_EMBEDED_ID")}&room=DEFAULT_ROOM"></script>`
};


module.exports = DB;
