"use strict";

app.Settlement = [];
app.Settlement.floatval = function(num)
{
    return (parseFloat(num) || 0);
}
app.Settlement.preg_replace = function(pattern,
	replacement, string)
{
	let _flag = pattern.substr(pattern.lastIndexOf(pattern[0]) + 1);
	_flag = (_flag !== '') ? _flag : 'g';
	const _pattern = pattern.substr(1, pattern.lastIndexOf(pattern[0]) - 1);
	const regex = new RegExp(_pattern, _flag);
	const result = string.replace(regex, replacement);
	return result;
}

app.Settlement.preg_match_all = function(regex, str) {
//   return [...str.matchAll(new RegExp(regex, 'g'))].reduce((acc, group) => {
//     group.filter((element) => typeof element === 'string').forEach((element, i) => {
//       if (!acc[i]) acc[i] = [];
//       acc[i].push(element);
//     });

//     return acc;
//   }, []);
    return str.match(regex);
}


app.Settlement.strrchr = function(haystack, needle)
{
	let pos = 0
	if (typeof needle !== 'string')
	{
		needle = String.fromCharCode(parseInt(needle, 10))
	}
	needle = needle.charAt(0)
	pos = haystack.lastIndexOf(needle)
	if (pos === -1)
	{
		return '';
	}
	return haystack.substr(pos)
}
app.Settlement.number_format = function(number,
	decimals = 0,
	dec_point = ".",
	thousands_sep = ",")
{
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		s = '',
		toFixedFix = function(n, prec)
		{
			var k = Math.pow(10, prec);
			return '' + (Math.round(n * k) / k).toFixed(prec);
		};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' +
		Math.round(n)).split('.');
	if (s[0].length > 3)
	{
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,
			thousands_sep);
	}
	if ((s[1] || '').length < prec)
	{
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec_point);
}
