/* global DB */

const router = require('express').Router();

router.use((req, res, next) => {
  // Set lock on embed code setup
  next();
});

router.get('/', (req, res) => {
  res.render('home');
});

router.get('/join/:meeting_id', (req, res, next) => {
  const m = req.params.meeting_id;
  console.log("m", m);
  if (m == null) {
    next();
    return;
  }
  const embed_code = DB.embed_code.replace('DEFAULT_ROOM', `meeting${m.id}`);

  if (!embed_code) {
    res.render('embed_not_set');
    return;
  }

  res.render('meeting', { embed_code: embed_code, meetingId: m });
});

module.exports = router;
