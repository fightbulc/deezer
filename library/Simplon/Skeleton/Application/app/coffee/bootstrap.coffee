require.config
  paths:
    jquery: '/assets/vendor/jquery/jquery-1.7.2.beta'
    underscore: '/assets/vendor/backbone/underscore-amd-1.3.1'
    backbone: '/assets/vendor/backbone/backbone-amd-0.9.1'
    snakeface: '/assets/library/snakeface/snakeface'
    pubsub: '/assets/library/snakeface/lib/pubsub'

#################################################

require ['matrix'], (matrix) -> matrix()