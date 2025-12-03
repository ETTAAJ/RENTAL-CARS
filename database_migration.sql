-- Migration: Add specifications column to cars table
-- Run this SQL to add the specifications field

ALTER TABLE cars ADD COLUMN specifications JSON DEFAULT NULL;

-- Update existing cars with default specifications
UPDATE cars SET specifications = JSON_OBJECT(
    'gear_box', 'Automat',
    'fuel', '95',
    'doors', '4',
    'air_conditioner', 'Yes',
    'seats', '5',
    'distance', '500 km'
) WHERE specifications IS NULL;

