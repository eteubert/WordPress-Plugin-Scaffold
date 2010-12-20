require 'choice'
require 'erb'

Choice.options do
  
  option :name, :required => true do
    short '-n'
    long '--name=STRING'
    desc 'The name of the plugin'
  end
  
end

name = Choice.choices.name

template = ERB.new File.read('templates/main.rb')

puts template.result