require 'choice'
require 'erb'
require 'fileutils'

BASEDIR = File.dirname(__FILE__)

class String
  def slug
    #strip the string
    ret = self.strip

    #blow away apostrophes
    ret.gsub! /['`]/,""

    # @ --> at, and & --> and
    ret.gsub! /\s*@\s*/, " at "
    ret.gsub! /\s*&\s*/, " and "

    #replace all non alphanumeric, underscore or periods with underscore
    ret.gsub! /\s*[^A-Za-z0-9\.\-]\s*/, '_'  

    #convert double underscores to single
    ret.gsub! /_+/,"_"

    #strip off leading/trailing underscore
    ret.gsub! /\A[_\.]+|[_\.]+\z/,""

    ret.downcase
  end
end

Choice.options do
  
  option :name, :required => true do
    short '-n'
    long '--name=STRING'
    desc 'The name of the plugin'
  end
  
end

name = Choice.choices.name

# create dirs
FileUtils.makedirs([
  'app/controllers',
  'app/views',
  'languages',
  'public',
  'public/css',
  'public/images',
  'public/javascript',
])

# touch files which will be empty
["public/css/#{name.slug}.css", "public/javascript/#{name.slug}.css"].each do |f|
  FileUtils.touch f
end

# render templates
template = ERB.new File.read(BASEDIR + '/templates/plugin_name.rb')

main = File.new("#{name.slug}.php", "w")
main.write(template.result)
main.close

# copy application controller
FileUtils.copy BASEDIR + '/templates/ApplicationController.php', 'app/controllers/'