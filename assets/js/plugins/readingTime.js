/*!
Name: Reading Time
Dependencies: jQuery
Author: Michael Lynch
Author URL: http://michaelynch.com
Date Created: August 14, 2013
Date Updated: May 2, 2018
Licensed under the MIT license
*/
!(function (n) {
  n.fn.readingTime = function (e) {
    const t = n(this);
    let i, r, a, s, u, g, l, m, o;
    this.settings = n.extend(
      {},
      {
        readingTimeTarget: '.eta',
        readingTimeAsNumber: !1,
        wordCountTarget: null,
        wordsPerMinute: 270,
        round: !0,
        lang: 'en',
        lessThanAMinuteString: '',
        prependTimeString: '',
        prependWordString: '',
        remotePath: null,
        remoteTarget: null,
        success: function () {},
        error: function () {},
      },
      e
    );
    const d = this.settings,
      T = function (e) {
        '' !== e.text
          ? ((s = e.text.trim().split(/\s+/g).length),
            (i = d.wordsPerMinute / 60),
            (u = s / i),
            (g = Math.floor(u / 60)),
            (l = Math.round(u - 60 * g)),
            (m = `${g}:${l}`),
            d.round
              ? g > 0
                ? n(d.readingTimeTarget).text(
                    d.prependTimeString +
                      g +
                      (d.readingTimeAsNumber ? '' : ' ' + a)
                  )
                : n(d.readingTimeTarget).text(
                    d.readingTimeAsNumber ? g : d.prependTimeString + r
                  )
              : n(d.readingTimeTarget).text(d.prependTimeString + m),
            '' !== d.wordCountTarget &&
              void 0 !== d.wordCountTarget &&
              n(d.wordCountTarget).text(d.prependWordString + s),
            (o = {
              wpm: d.wordsPerMinute,
              words: s,
              eta: { time: m, minutes: g, seconds: u },
            }),
            d.success.call(this, o))
          : d.error.call(this, {
              error: 'The element does not contain any text',
            });
      };
    return this.length
      ? ('ar' == d.lang
          ? ((r = d.lessThanAMinuteString || 'أقل من دقيقة'), (a = 'دقيقة'))
          : 'cz' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Méně než minutu'), (a = 'min'))
          : 'da' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Mindre end et minut'),
            (a = 'min'))
          : 'de' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Weniger als eine Minute'),
            (a = 'min'))
          : 'es' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Menos de un minuto'), (a = 'min'))
          : 'fr' == d.lang
          ? ((r = d.lessThanAMinuteString || "Moins d'une minute"), (a = 'min'))
          : 'hu' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Kevesebb mint egy perc'),
            (a = 'perc'))
          : 'is' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Minna en eina mínútu'),
            (a = 'min'))
          : 'it' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Meno di un minuto'), (a = 'min'))
          : 'nl' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Minder dan een minuut'),
            (a = 'min'))
          : 'no' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Mindre enn ett minutt'),
            (a = 'min'))
          : 'pl' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Mniej niż minutę'), (a = 'min'))
          : 'ru' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Меньше минуты'), (a = 'мин'))
          : 'sk' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Menej než minútu'), (a = 'min'))
          : 'sv' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Mindre än en minut'), (a = 'min'))
          : 'tr' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Bir dakikadan az'), (a = 'dk'))
          : 'uk' == d.lang
          ? ((r = d.lessThanAMinuteString || 'Менше хвилини'), (a = 'хв'))
          : ((r = d.lessThanAMinuteString || 'Less than a minute'),
            (a = 'min')),
        t.each(function (e) {
          null != d.remotePath && null != d.remoteTarget
            ? n.get(d.remotePath, function (e) {
                let t = document.createElement('div');
                (t.innerHTML = e),
                  T({ text: n(t).find(d.remoteTarget).text() });
              })
            : T({ text: t.text() });
        }),
        !0)
      : (d.error.call(this, { error: 'The element could not be found' }), this);
  };
})(jQuery);
