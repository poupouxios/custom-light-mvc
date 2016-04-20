class CreatePage < ActiveRecord::Migration
  def self.up
    create_table :pages, options: 'DEFAULT CHARSET=utf8' do |t|
      t.string :title
      t.string :slug
      t.string :content
      t.timestamps
    end
  end

  def self.down
    drop_table :pages
  end

end
