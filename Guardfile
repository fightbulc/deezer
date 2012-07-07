# A sample Guardfile
# More info at https://github.com/guard/guard#readme

#################################################
#
# app
#

guard 'coffeescript', :all_on_start => true, :bare => true, :input => 'app/coffee',:output => 'public/assets/app/js'

 guard 'less', :all_on_start => true, :all_after_change => TRUE, :output => 'public/assets/app/css' do
  watch(%r{^app/less/.+\.less$})
 end

#################################################
#
# snakeface
#

guard 'coffeescript', :all_on_start => true, :bare => true, :input => 'library/Snakeface',:output => 'public/assets/library/snakeface'