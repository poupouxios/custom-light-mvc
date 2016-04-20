class CreateUsers < ActiveRecord::Migration
  def self.up
    create_table :users, options: 'DEFAULT CHARSET=utf8' do |t|
      t.string :title
      t.string :firstname
      t.string :surname
      t.string :telephone
      t.string :email
      t.timestamps
    end
  end

  def self.down
    drop_table :users
  end
end
