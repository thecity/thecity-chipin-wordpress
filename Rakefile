desc "Package the project for deploying to WordPress.org"
task :package do
  cmds = [
    'cp -R ./ ../the-city-chipin',
    'cd ../',
    'rm -rf ./the-city-chipin/.git',
    'rm -f ./the-city-chipin/README.rdoc',
    'rm -f ./the-city-chipin/changelog',
    'rm -f ./the-city-chipin/Rakefile',
    'rm -f ./the-city-chipin/notes.txt',
    'zip -r the-city-chipin.zip ./the-city-chipin',
    'rm -rf the-city-chipin'
  ]

  `#{cmds.join(' ; ')}`
end

desc "Copy files that would go in the package [for deploying to WordPress.org] to a path"
task :package_to_path, :path do |t, args|
  cmds = [
    'cp -R ./ ../the-city-chipin',
    'cd ../',
    'rm -rf ./the-city-chipin/.git',
    'rm -f ./the-city-chipin/README.rdoc',
    'rm -f ./the-city-chipin/changelog',
    'rm -f ./the-city-chipin/Rakefile',
    'rm -f ./the-city-chipin/notes.txt',
    "cp -R ./the-city-chipin/* #{args[:path]}",
    'rm -rf the-city-chipin'
  ]

  `#{cmds.join(' ; ')}`
end