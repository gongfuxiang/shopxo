function toIntArray(v) {
  const ret = [];
  const version = v.split('.');

  for (let i = 0; i < version.length; i++) {
    ret.push(parseInt(version[i], 10));
  }

  return ret;
}

const calcScrollLeft = (windowWidth, tabWidth, current) => {
  let scrollInit = current * windowWidth * tabWidth;

  if (current <= 2) {
    scrollInit = 0;
  } else {
    scrollInit = (current - 2) * windowWidth * tabWidth;
  }

  return scrollInit;
};

const compareVersion = (v) => {
  const targetVersion = toIntArray('1.10.0');
  const version = toIntArray(v);
  let ret = 0;

  for (let i = 0, n1, n2; i < version.length; i++) {
    n1 = targetVersion[i];
    n2 = version[i];

    if (n1 > n2) {
      ret = -1;
      break
    }

    if (n1 < n2) {
      ret = 1;
      break;
    }
  }

  return ret;
}

export default {
  calcScrollLeft,
  compareVersion,
};
