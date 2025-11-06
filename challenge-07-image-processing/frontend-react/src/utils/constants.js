export const APP_NAME = 'Image Processing Application';
export const API_BASE_URL = '/api';
export const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
export const ALLOWED_FILE_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

export const ROUTES = {
  HOME: '/',
  BATCH: '/batch',
  OPTIMIZE: '/optimize'
};

export const FILTER_TYPES = {
  BRIGHTNESS: 'brightness',
  CONTRAST: 'contrast',
  SATURATION: 'saturation',
  GRAYSCALE: 'grayscale',
  SEPIA: 'sepia',
  BLUR: 'blur'
};

export const DEFAULT_FILTERS = {
  [FILTER_TYPES.BRIGHTNESS]: 100,
  [FILTER_TYPES.CONTRAST]: 100,
  [FILTER_TYPES.SATURATION]: 100,
  [FILTER_TYPES.GRAYSCALE]: 0,
  [FILTER_TYPES.SEPIA]: 0,
  [FILTER_TYPES.BLUR]: 0
};

export const BATCH_STATUS = {
  PENDING: 'pending',
  PROCESSING: 'processing',
  COMPLETED: 'completed',
  FAILED: 'failed'
};