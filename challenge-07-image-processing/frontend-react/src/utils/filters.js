export const filterTypes = {
  BRIGHTNESS: 'brightness',
  CONTRAST: 'contrast',
  SATURATION: 'saturation',
  GRAYSCALE: 'grayscale',
  SEPIA: 'sepia',
  BLUR: 'blur',
  INVERT: 'invert'
};

export const defaultFilters = {
  [filterTypes.BRIGHTNESS]: 100,
  [filterTypes.CONTRAST]: 100,
  [filterTypes.SATURATION]: 100,
  [filterTypes.GRAYSCALE]: 0,
  [filterTypes.SEPIA]: 0,
  [filterTypes.BLUR]: 0,
  [filterTypes.INVERT]: 0
};

export const applyFiltersToCanvas = (ctx, filters, width, height) => {
  let filterString = '';
  
  if (filters[filterTypes.BRIGHTNESS] !== 100) {
    filterString += `brightness(${filters[filterTypes.BRIGHTNESS]}%) `;
  }
  
  if (filters[filterTypes.CONTRAST] !== 100) {
    filterString += `contrast(${filters[filterTypes.CONTRAST]}%) `;
  }
  
  if (filters[filterTypes.SATURATION] !== 100) {
    filterString += `saturate(${filters[filterTypes.SATURATION]}%) `;
  }
  
  if (filters[filterTypes.GRAYSCALE] > 0) {
    filterString += `grayscale(${filters[filterTypes.GRAYSCALE]}%) `;
  }
  
  if (filters[filterTypes.SEPIA] > 0) {
    filterString += `sepia(${filters[filterTypes.SEPIA]}%) `;
  }
  
  if (filters[filterTypes.BLUR] > 0) {
    filterString += `blur(${filters[filterTypes.BLUR]}px) `;
  }
  
  if (filters[filterTypes.INVERT] > 0) {
    filterString += `invert(${filters[filterTypes.INVERT]}%) `;
  }
  
  ctx.filter = filterString.trim();
};