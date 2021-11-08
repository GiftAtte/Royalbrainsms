function vowelCount(str) {
    let vowel = 'aeiou'
    let count=0
    for (let char of str) {
        if (vowel.includes(char)) {
          count++
        }
        console.log(count)
    }
}



function timeConversion(s) {
    let militaryTime = '';
    let ss = s.split(':')
    let meridian = ss[ss.length - 1].slice(2)
    let seconds = ss[ss.length - 1].slice(0, 2)
    let minute = ss[ss.length - 2]
     let hour = Number(ss[0])

    if (meridian == 'AM' && hour == 12)
    {
        return militaryTime = '00' + ':' + minute + ':' + seconds;
    } else if (meridian == 'PM' && hour < 12)
    {
        return militaryTime = (hour + 12).toString() + ':' + minute + ':' + seconds;
    } else return militaryTime = ss[0] + ':' + minute + ':' + seconds;
}
